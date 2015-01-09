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
            ->addArgument('application_name', InputArgument::REQUIRED, 'The application\'s name')
            ->addArgument('is_external', InputArgument::REQUIRED, 'Do you want an external address ?')
            ->setHelp(<<<EOF
The <info>%command.name%</info> generate you a multisig address, and insert it to DB.

<info>php %command.full_name% applicationName true</info>

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

        $applications = $em->getRepository('DizdaAppBundle:Application')->findOneByName($input->getArgument('application_name'));
        $isExternal   = $input->getArgument('is_external') === 'true';

        $address = $manager->create($applications, $isExternal);

        $output->writeln(sprintf('The generated address is: <info>%s</info>.', $address->getValue()));

        $em->flush();
    }
}
