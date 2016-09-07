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

class ManualUpdateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('episodes:update')
             ->setDescription('Marks episodes for update by code pattern');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container     = $this->getContainer();


    }
}