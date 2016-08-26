<?php
namespace AppBundle\Service\Sync;

use AppBundle\Entity\Episode as EpisodeEntity;
use AppBundle\Entity\EpisodeRepository;
use AppBundle\Exception\PullException;

class Episode extends Entity
{
    /**
     * @var EpisodeRepository
     */
    protected $repo;
    protected $name = 'Episode';

    public function add(array $data)
    {
        if (!isset($data['code']) || $data['code'] == '') {
            throw new PullException('Code can not be empty');
        }

        $episode = $this->repo->getOne($data['code']);
        if (null === $episode) {
            $episode = new EpisodeEntity();
            $episode->setCode($data['code']);
        }

        /**
         * @var \AppBundle\Entity\ShowRepository $showRepo
         * @var \AppBundle\Entity\SeasonRepository $seasonRepo
         */
        $showRepo = $this->em->getRepository('AppBundle:Show');
        $show     = $showRepo->getOne($data['program']);
        if (null === $show) {
            throw new PullException(sprintf(
                'Show "%s" does not exists',
                $data['program']
            ));
        }

        $seasonRepo = $this->em->getRepository('AppBundle:Season');
        $season     = $seasonRepo->getOne($show, $data['season']);
        if (null === $season) {
            throw new PullException(sprintf(
                'Season "%s" does not exists',
                $data['season']
            ));
        }

        $episode->setTitle($data['title']);
        $episode->setDesc($data['desc']);
        $episode->setPublish(\DateTime::createFromFormat('Y-m-d H:i:s', $data['publish_time']));
        $episode->setSeason($season);
        $episode->setProgram($show);
        $episode->setLanguage($data['lang']);
        $episode->setAuthor($data['author']);
        $episode->setModified($this->modified);
        $episode->setHd($data['hd']);
        $episode->setFormat($data['format']);
        $episode->setDuration($data['duration']);
        $episode->setTrash(0);

        // Tags
        $tags = isset($data['tags']) ? explode(',', $data['tags']) : [];
        $tags = array_map(function($tag){
            return trim($tag);
        }, $tags);
        $episode->setTags($tags);

        $this->em->persist($episode);
        $this->em->flush();
    }

    public function update(array $data)
    {
        $this->add($data);
    }

    public function delete(array $data)
    {
        if (!isset($data['code']) || $data['code'] == '') {
            throw new PullException('Code can not be empty');
        }

        $episode = $this->repo->getOne($data['code']);
        if (null !== $episode) {
            $episode->setTrash(1);
            $this->em->persist($episode);
            $this->em->flush();
        }
    }

    public function youtube(array $data)
    {
    }
}
