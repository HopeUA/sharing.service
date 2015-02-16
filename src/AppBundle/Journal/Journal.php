<?php
namespace AppBundle\Journal;

use AppBundle\Exception\JournalException;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Serializer\Serializer;

class Journal
{
    /**
     * @var EntityManager
     */
    private $em;
    /**
     * @var Serializer
     */
    private $serializer;

    public function __construct(EntityManager $em, Serializer $serializer)
    {
        $this->em         = $em;
        $this->serializer = $serializer;
    }

    public function write(Entry $entry)
    {
        $query = 'INSERT INTO journal (uid, type, data, moment) VALUES (:uid, :type, :data, :moment)';
        $stmt  = $this->em->getConnection()->prepare($query);

        $stmt->bindValue('uid', $entry->getUid());
        $stmt->bindValue('type', $entry->getType());
        $stmt->bindValue('data', $this->serializer->encode($entry->getData(), 'json'));
        $stmt->bindValue('moment', $entry->getMoment());

        $result = $stmt->execute();

        if (!$result) {
            throw new JournalException('Error while writing the journal entry');
        }
    }
}
