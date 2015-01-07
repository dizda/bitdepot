<?php

namespace Dizda\Bundle\AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateNewAddressCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('dizda:generate:address')
            ->setDescription('Address')
            ->setHelp(<<<EOF
The <info>%command.name%</info> command clears the application cache for a given environment
and debug mode:

coucou

<info>php %command.full_name% --env=dev</info>
<info>php %command.full_name% --env=prod --no-debug</info>

EOF
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em      = $this->getContainer()->get('doctrine.orm.default_entity_manager');
        $manager = $this->getContainer()->get('dizda_app.manager.address');


        $applications = $em->getRepository('DizdaAppBundle:Application')->findOneByName('ptc');

        $manager->create($applications, true);
//
//        foreach ($applications as $application) {
//            $outputs = $manager->search($application);
//
//            if ($outputs) {
//                $manager->create($application, $outputs);
//            }
//        }
    }

}
