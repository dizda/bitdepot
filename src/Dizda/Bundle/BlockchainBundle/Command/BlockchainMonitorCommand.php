<?php

namespace Dizda\Bundle\BlockchainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BlockchainMonitorCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('dizda:blockchain:monitor')
            ->setDefinition(array(
                new InputArgument('Monitor addresses')
            ))
            ->setDescription('Monitor addresses')
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
        $blockchain = $this->getContainer()->get('dizda_blockchain.blockchain.manager');
        $blockchain->monitor();
        var_dump('AZSAZDAZ');
        var_dump('AZSAZDAZ');
        var_dump('AZSAZDAZ');
        var_dump('AZSAZDAZ');
    }
}
