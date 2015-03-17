<?php

namespace Ferus\FCFSBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class RegistrationCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('fcfs:registration:send-email')
            ->setDescription('Envoie le mail d\'inscription pour un event à tous les étudiants')
            ->addArgument('event', InputArgument::REQUIRED, 'ID de l\'event à envoyer')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $event = $input->getArgument('event');

        if ($this->getContainer()->get('ferus.fcfs_bundle.services.mailer')->sendRegistrationEmail($event))
            $output->writeln('envoie terminé');
    }
}