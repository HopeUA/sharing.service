<?php
namespace AppBundle\Service\Sync;

use AppBundle\Entity\Season as SeasonEntity;
use AppBundle\Entity\SeasonRepository;
use AppBundle\Exception\PullException;
use AppBundle\Helper\ListParameters;

class Season extends Entity
{
    /**
     * @var SeasonRepository
     */
    protected $repo;
    protected $name = 'Season';

    public function add(array $data)
    {
        if (!isset($data['uid']) || $data['uid'] == '') {
            throw new PullException('Uid can not be empty');
        }

        /**
         * @var \AppBundle\Entity\ShowRepository $showRepo
         */
        $showRepo = $this->em->getRepository('AppBundle:Show');
        $show     = $showRepo->getOne($data['program']);
        if (null === $show) {
            throw new PullException(sprintf(
                'Show "%s" does not exists',
                $data['program']
            ));
        }

        $season = $this->repo->getOne($show, $data['uid']);
        if (null === $season) {
            $season = new SeasonEntity();
            $season->setUid($data['uid']);
            $season->setShow($show);
            $season->setSort($this->getLastSort($show->getId()) + 1);
        }

        $season->setTitle($data['title']);
        $season->setModified($this->modified);

        $this->em->persist($season);
        $this->em->flush();
    }

    public function update(array $data)
    {
        $this->add($data);
    }

    public function delete(array $data)
    {
        if (!isset($data['uid']) || $data['uid'] == '') {
            throw new PullException('Uid can not be empty');
        }

        /**
         * @var \AppBundle\Entity\ShowRepository $showRepo
         */
        $showRepo = $this->em->getRepository('AppBundle:Show');
        $show     = $showRepo->getOne($data['program']);
        if (null === $show) {
            throw new PullException(sprintf(
                'Show "%s" does not exists',
                $data['program']
            ));
        }

        $season = $this->repo->getOne($show, $data['uid']);

        if (null !== $season) {
            if (count($season->getEpisodes())) {
                throw new PullException(sprintf(
                    'Delete error: season "%s-%s" should be empty',
                    $show->getCode(),
                    $season->getUid()
                ));
            }

            $this->em->remove($season);
            $this->em->flush();
        }
    }

    private function getLastSort($showId)
    {
        $params = new ListParameters();
        $params->set('showId', $showId);
        $params->setPage(1);
        $params->setLimit(100);

        $seasons = $this->repo->getList($params);
        if (count($seasons) == 0) {
            return 0;
        }

        $last = array_pop($seasons);

        return $last->getSort();
    }
}
