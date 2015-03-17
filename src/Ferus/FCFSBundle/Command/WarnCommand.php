<?php

namespace Ferus\FCFSBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class WarnCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('fcfs:warn:send-email')
            ->setDescription('Envoie le mail de notification pour un event à tous les étudiants')
            ->addArgument('event', InputArgument::REQUIRED, 'ID de l\'event à envoyer')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $event = $input->getArgument('event');

        if ($this->getContainer()->get('ferus.fcfs_bundle.services.mailer')->sendWarnEmail($event))
            $output->writeln('envoie terminé');
    }
}