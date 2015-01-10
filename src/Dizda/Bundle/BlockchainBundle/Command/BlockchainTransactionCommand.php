<?php

namespace Dizda\Bundle\BlockchainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


class BlockchainTransactionCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('dizda:blockchain:transaction')
            ->setDefinition(array(
                new InputArgument('txid')
            ))
            ->setDescription('Get transaction with txid')
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
        $txid = $input->getArgument('txid');

        $transaction = $this->getContainer()->get('dizda_blockchain.blockchain.provider')
            ->getBlockchain()
            ->getTransaction($txid)
        ;

        var_dump($transaction);
    }

}
