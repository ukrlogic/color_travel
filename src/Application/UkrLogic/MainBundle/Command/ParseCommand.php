<?php
/**
 * Created by PhpStorm.
 * User: vladow
 * Date: 29.12.14
 * Time: 21:02
 */

namespace Application\UkrLogic\MainBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ParseCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('tours:parse')
            ->setDescription('Parse tours from akkord tour gateway')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->getContainer()->get('application_ukrlogic_akkordtourbundle.service.tourparser')->loadTours();
        } catch (\Exception $e) {
            $output->writeln('Error: ' . $e->getMessage());die;
        }
    }
}