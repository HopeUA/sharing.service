<?php
namespace AppBundle\Service\Sync;

use AppBundle\Entity\Category as CategoryEntity;
use AppBundle\Entity\CategoryRepository;
use AppBundle\Exception\PullException;

class Category extends Entity
{
    /**
     * @var CategoryRepository
     */
    protected $repo;
    protected $name = 'Category';

    public function add(array $data)
    {
        if (!isset($data['code']) || $data['code'] == '') {
            throw new PullException('Uid can not be empty');
        }

        $category = $this->repo->getByUid($data['code']);
        if (null === $category) {
            $category = new CategoryEntity();
            $category->setUid($data['code']);
        }

        $category->setTitleRu($data['title_ru']);
        $category->setTitleUa($data['title_ua']);
        $category->setSort($data['order']);
        $category->setModified($this->modified);

        $this->em->persist($category);
        $this->em->flush();
    }

    public function update(array $data)
    {
        $this->add($data);
    }

    public function delete(array $data)
    {
        if (!isset($data['code']) || $data['code'] == '') {
            throw new PullException('Uid can not be empty');
        }

        $category = $this->repo->getByUid($data['code']);
        if (null !== $category) {
            if (count($category->getShows())) {
                throw new PullException(sprintf(
                    'Delete error: Category "%s" should be empty',
                    $category->getUid()
                ));
            }

            $this->em->remove($category);
            $this->em->flush();
        }
    }
}
