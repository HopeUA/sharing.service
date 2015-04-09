<?php
namespace AppBundle\Service;

use AppBundle\Exception\PullException;
use Doctrine\ORM\EntityManager;
use GuzzleHttp;
use Monolog\Logger;
use AppBundle\Service\Sync\Entity;

class Sync
{
    private $url;
    private $token;
    /**
     * @var GuzzleHttp\Client
     */
    private $client;

    /**
     * @var Logger
     */
    private $log;

    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em, $url, $token = '')
    {
        $this->em     = $em;
        $this->url    = $url;
        $this->token  = $token;
        $this->client = new GuzzleHttp\Client();
    }

    public function setLog(Logger $logger)
    {
        $this->log = $logger;
    }

    public function run(Config $configManager)
    {
        $this->log->info('START sync');

        $config = $configManager->load();
        $from   = $config['entry.id'];
        $page   = 1;
        $done   = false;

        while (!$done) {
            $this->log->info('HTTP Request: Media Service');
            $response = $this->client->get($this->url, [
                'query' => [
                    'from' => $from,
                    'page' => $page
                ],
            ]);
            $this->log->info(sprintf(
                'HTTP Response [%d] from %s',
                $response->getStatusCode(),
                $response->getEffectiveUrl()
            ));

            switch ($response->getStatusCode()) {
                case 200:
                    $responseData = $response->json();
                    foreach ($responseData['data'] as $action) {
                        try {
                            $this->handle($action);

                            $this->log->info(sprintf(
                                '[%s (%d) – %s] Done',
                                $action['type'],
                                $action['id'],
                                $action['uid']
                            ));

                            $config['entry.id'] = (int)$action['id'];
                            $configManager->save($config);

                        } catch (\Exception $e) {
                            $this->log->error(sprintf(
                                '[%s (%d) – %s] Action handle error: %s',
                                $action['type'],
                                $action['id'],
                                $action['uid'],
                                $e->getMessage()
                            ));
                            throw $e;
                        }
                    }

                    $page++;
                    break;
                case 404:
                    $done = true;
                    break;
                default:
                    $this->log->error(sprintf(
                        'HTTP error [%d]: %s',
                        $response->getStatusCode(),
                        $response->getBody()
                    ));
                    $done = true;
            }
        }
    }

    public function handle($action)
    {
        list($entity, $task) = explode('.', $action['type']);

        $manager = $this->getEntityManager($entity);
        if (!method_exists($manager, $task)) {
            throw new PullException(sprintf(
                'Action "%s" for entity "%s" not found',
                $entity,
                $task
            ));
        }

        $manager->setModified($action['moment']);
        $manager->{$task}($action['data']);
    }

    /**
     * @param $entityName
     *
     * @return Entity
     */
    private function getEntityManager($entityName)
    {
        $entityClass = '\\AppBundle\\Service\\Sync\\' . ucfirst($entityName);
        if (!class_exists($entityClass)) {
            throw new PullException(sprintf('Entity type "%s" not found', $entityName));
        }

        return new $entityClass($this->em);
    }
}