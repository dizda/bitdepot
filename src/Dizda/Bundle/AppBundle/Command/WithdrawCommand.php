<?php

namespace Dizda\Bundle\AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WithdrawCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('dizda:app:withdraw')
            ->setDefinition(array(
                new InputArgument('address')
            ))
            ->setDescription('Withdraw')
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
        $manager = $this->getContainer()->get('dizda_app.manager.withdraw');

        $keychains = $em->getRepository('DizdaAppBundle:Keychain')->findAll();

        // TODO: Fetch Keychains whereas Applications

        foreach ($keychains as $keychain) {
            $outputs = $manager->search($keychain);

            if ($outputs) {
                $manager->create($keychain, $outputs);
            }
        }
    }
}
