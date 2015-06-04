<?php

namespace Dizda\Bundle\AppBundle\Service;

use Dizda\Bundle\AppBundle\Entity\Address;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\SerializationContext;
use Monolog\Logger;
use Symfony\Component\Process\Process;
use JMS\Serializer\Serializer;

/**
 * Class BitcoreService
 *
 * @author Jonathan Dizdarevic <dizda@dizda.fr>
 */
class BitcoreService
{
    /**
     * @var \Symfony\Component\Serializer\Serializer
     */
    private $serializer;

    /**
     * @var \Monolog\Logger
     */
    private $logger;

    /**
     * @var string
     */
    private $rootPath;

    /**
     * @var string
     */
    private $nodejsPath;

    /**
     * @param Serializer $serializer
     * @param Logger     $logger
     * @param string     $rootPath
     * @param string     $nodejsPath
     */
    public function __construct(Serializer $serializer, Logger $logger, $rootPath, $nodejsPath)
    {
        $this->serializer = $serializer;
        $this->logger     = $logger;
        $this->rootPath   = $rootPath;
        $this->nodejsPath = $nodejsPath;
    }

    /**
     * Transaction
     *
     * @param ArrayCollection $inputs
     * @param ArrayCollection $outputs
     * @param Address         $changeAddress
     *
     * @throws \RuntimeException
     *
     * @return mixed|object
     */
    public function buildTransaction(ArrayCollection $inputs, ArrayCollection $outputs, Address $changeAddress = null)
    {
        $params = [
            'inputs'  => $inputs,
            'outputs' => $outputs,
            'changeAddress' => $changeAddress
        ];

        $context = (new SerializationContext())->setGroups('TransactionBuilder');

        $process = new Process(
            sprintf(
                '%s ./node/generate_transaction.js \'%s\'',
                $this->nodejsPath, // we specify the path of nodejs
                $this->serializer->serialize($params, 'json', $context)
            ),
            $this->rootPath
        );
        $process->run();

        if (!$process->isSuccessful()) {
            $this->logger->error('Can not generate transaction.', [ $process->getErrorOutput() ]);

            // Process failed
            throw new \RuntimeException($process->getErrorOutput());
        }

        return $this->serializer->deserialize($process->getOutput(), 'array', 'json');
    }

    /**
     * Broadcast a transaction
     *
     * @param string $serializedTransaction
     *
     * @throws \RuntimeException
     *
     * @return string txid
     */
    public function broadcastTransaction($serializedTransaction)
    {
        if (!preg_match('/^[0-9a-f]+$/', $serializedTransaction)) {
            throw new \RuntimeException('Wrong raw transaction hash.');
        }

        $process = new Process(
            sprintf(
                '%s ./node/broadcast_transaction.js \'%s\'',
                $this->nodejsPath, // we specify the path of nodejs
                $serializedTransaction
            ),
            $this->rootPath
        );
        $process->run();

        if (!$process->isSuccessful()) {
            $this->logger->error('Can not broadcast the transaction.', [ $process->getErrorOutput() ]);

            // Process failed
            throw new \RuntimeException($process->getErrorOutput());
        }

        if (!preg_match('/^[0-9a-f]+$/', trim($process->getOutput()))) {
            throw new \RuntimeException(sprintf(
                'Malformed txid received from broadcast_transaction.js %s.',
                $process->getOutput()
            ));
        }

        return trim($process->getOutput());
    }
}
