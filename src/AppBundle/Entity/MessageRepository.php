<?php
namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\Orm\NoResultException;

/**
 * MessageRepository
 */
class MessageRepository extends EntityRepository
{
    const MS_NEW   = 0;
    const MS_TAKEN = 1;
    const MS_DONE  = 2;

    public function publish($uid, $task, $data, $priority = 100, $published = 1, $group = 'main')
    {
        if (is_array($data)) {
            $data = json_encode($data, JSON_UNESCAPED_UNICODE);
        }

        // Проверка на дубликаты
        $query = $this->getEntityManager()
                      ->createQuery(
                       'SELECT COUNT(m.id) FROM AppBundle:Message m
                         WHERE m.uid = :uid
                           AND m.task = :task
                           AND m.data = :m_data
                           AND m.published = 1
                           AND m.group = :group
                           AND (m.status = :status_t OR m.status = :status_n)')
                      ->setMaxResults(1)
                      ->setParameter('uid', $uid)
                      ->setParameter('task', $task)
                      ->setParameter('m_data', $data)
                      ->setParameter('group', $group)
                      ->setParameter('status_n', self::MS_NEW)
                      ->setParameter('status_t', self::MS_TAKEN);
        try {
            $check = $query->getSingleScalarResult();
        } catch(NoResultException $e) {
            $check = 0;
        }

        if ($check > 0) {
            return;
        }

        $message = new Message();
        $message->setUid($uid);
        $message->setTask($task);
        $message->setData($data);
        $message->setPriority($priority);
        $message->setPublished($published);
        $message->setGroup($group);

        $em = $this->getEntityManager();
        $em->persist($message);
        $em->flush();
    }
}
