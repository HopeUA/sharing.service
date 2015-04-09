<?php
namespace AppBundle\Command;

use AppBundle\Service\Config;
use AppBundle\Service\Sync;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Symfony\Bridge\Monolog\Handler\ConsoleHandler;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

class PullCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('remote:pull')
             ->setDescription('Pulls changes from the remote storage');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container     = $this->getContainer();

        // init config
        $configManager = new Config($container->getParameter('kernel.root_dir') . '/config/sync.yml');
        $config        = $configManager->load();

        // Init logger
        $logFile = $container->getParameter('kernel.root_dir') . '/logs/sync.log';
        $logger  = new Logger('sync');
        $logger->pushHandler(new StreamHandler($logFile, Logger::INFO));
        $logger->pushHandler(new ConsoleHandler($output));

        // Check running processes
        if ($config['process.id'] > 0) {
            if (file_exists('/proc/' . $config['process.id'])) {
                $logger->warning(sprintf(
                    'Another process (%d) is running',
                    $config['process.id']
                ));
                return;
            }

            $logger->warning(sprintf(
                'Dead process (%d). Reloading...',
                $config['process.id']
            ));
        }

        // Lock process
        $config['process.id']    = getmypid();
        $config['process.start'] = date('Y-m-d H:i:s');
        $config['process.end']   = 0;
        $configManager->save($config);

        $em = $container->get('doctrine')->getManager();

        try {
            $sync = new Sync($em, $container->getParameter('remote.url'));
            $sync->setLog($logger);
            $sync->run($configManager);

            $logger->info('END Sync. Success...');
        } catch (\Exception $e) {
            $logger->error('END Sync. Error...');
        }

        // Unlock process
        $config = $configManager->load();
        $config['process.id']  = 0;
        $config['process.end'] = date('Y-m-d H:i:s');
        $configManager->save($config);
    }
}