<?php
namespace AppBundle\Service\Sync;

use Doctrine\ORM\EntityManager;

abstract class Entity
{
    /**
     * @var EntityManager
     */
    protected $em;

    protected $repo;

    /**
     * @var string  Doctrine Entity name
     */
    protected $name;

    /**
     * @var \DateTime
     */
    protected $modified;

    public function __construct(EntityManager $em)
    {
        $this->em   = $em;
        $this->repo = $em->getRepository('AppBundle:' . $this->name);
    }

    abstract function add(array $data);
    abstract function update(array $data);
    abstract function delete(array $data);

    protected function init()
    {
    }

    public function setModified($date)
    {
        $this->modified = \DateTime::createFromFormat('Y-m-d H:i:s', $date);
    }
}
