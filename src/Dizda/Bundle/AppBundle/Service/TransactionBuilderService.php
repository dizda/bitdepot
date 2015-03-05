<?php

namespace Dizda\Bundle\AppBundle\Service;

use Dizda\Bundle\AppBundle\Entity\Address;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\SerializationContext;
use Monolog\Logger;
use Symfony\Component\Process\Process;
use JMS\Serializer\Serializer;

/**
 * Class TransactionBuilderService
 *
 * @author Jonathan Dizdarevic <dizda@dizda.fr>
 */
class TransactionBuilderService
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
    public function build(ArrayCollection $inputs, ArrayCollection $outputs, Address $changeAddress = null)
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
}
