<?php
namespace AppBundle\Journal;

use AppBundle\Exception\JournalException;
use Doctrine\ORM\EntityManager;
use JMS\Serializer\Serializer;

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
        $stmt->bindValue('data', $this->serializer->serialize($entry->getData(), 'json'));
        $stmt->bindValue('moment', $entry->getMoment()->format('Y-m-d H:i:s'));

        $result = $stmt->execute();

        if (!$result) {
            throw new JournalException('Error while writing the journal entry');
        }
    }
}
