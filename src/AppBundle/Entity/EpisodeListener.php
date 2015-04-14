<?php
namespace AppBundle\Entity;

use AppBundle\Service\Youtube\ChannelSelector;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;

class EpisodeListener
{
    public function postPersist(Episode $episode, LifecycleEventArgs $args)
    {
        $this->afterUpdate($episode, $args->getEntityManager());
    }

    public function postUpdate(Episode $episode, LifecycleEventArgs $args)
    {
        $uow       = $args->getEntityManager()->getUnitOfWork();
        $changeSet = $uow->getEntityChangeSet($episode);

        // DateTime fix for change comparsion
        unset($changeSet['modified']);
        if (isset($changeSet['publish'])) {
            $oldValue = $changeSet['publish'][0]->format('YmdHis');
            $newValue = $changeSet['publish'][1]->format('YmdHis');

            if ($oldValue == $newValue) {
                unset($changeSet['publish']);
            }
        }

        if (count($changeSet) > 0) {
            $this->afterUpdate($episode, $args->getEntityManager());
        }
    }

    private function afterUpdate(Episode $episode, EntityManager $em)
    {
        /**
         * @var \AppBundle\Entity\EpisodeRepository $episodeRepo
         */
        $episodeRepo = $em->getRepository('AppBundle:Episode');

        $channelSelector = new ChannelSelector($episode->getCode());
        $channel         = $channelSelector->program();
        $mqGroup         = 'yt-' . $channel;

        $uploaded = true;
        if ($episode->getMedia() === null) {
            $uploaded = false;
        } else {
            $sources = $episode->getMedia()->getVideo()->getSources();
            if (!isset($sources['youtube'])) {
                $uploaded = false;
            }
        }

        if ($uploaded) {
            $mqGroup = 'fast';
            $episodeRepo->updateOnYoutube($episode, $channel, $mqGroup);
        } else {
            $episodeRepo->compressForYoutube($episode, $channelSelector->compressor(), 'medium', $mqGroup);
            $episodeRepo->uploadOnYoutube($episode, $channel, $mqGroup);
        }

        $episodeRepo->updateSite($episode, $mqGroup);
    }
}