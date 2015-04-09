<?php
namespace AppBundle\Service\Sync;

use AppBundle\Entity\Show as ShowEntity;
use AppBundle\Entity\ShowRepository;
use AppBundle\Exception\PullException;

class Show extends Entity
{
    /**
     * @var ShowRepository
     */
    protected $repo;
    protected $name = 'Show';

    public function add(array $data)
    {
        if (!isset($data['code']) || $data['code'] == '') {
            throw new PullException('Code can not be empty');
        }

        $show = $this->repo->getOne($data['code']);
        if (null === $show) {
            $show = new ShowEntity();
            $show->setCode($data['code']);
        }

        /**
         * @var \AppBundle\Entity\CategoryRepository $categoryRepo
         */
        $categoryRepo = $this->em->getRepository('AppBundle:Category');
        $category     = $categoryRepo->getByUid($data['category']);
        if (null === $category) {
            throw new PullException(sprintf(
                'Category "%s" does not exists',
                $data['category']
            ));
        }

        $show->setTitle($data['title']);
        $show->setCategory($category);
        $show->setModified($this->modified);

        $this->em->persist($show);
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

        $show = $this->repo->getOne($data['code']);
        if (null !== $show) {
            if (count($show->getSeasons())) {
                throw new PullException(sprintf(
                    'Delete error: show "%s" should be empty',
                    $show->getCode()
                ));
            }

            $this->em->remove($show);
            $this->em->flush();
        }
    }
}
