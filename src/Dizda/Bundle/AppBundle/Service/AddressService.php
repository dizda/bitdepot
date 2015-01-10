<?php

namespace Dizda\Bundle\AppBundle\Service;

use Dizda\Bundle\AppBundle\Entity\Application;
use Monolog\Logger;
use Symfony\Component\Process\Process;
use JMS\Serializer\Serializer;

/**
 * Class AddressService
 *
 * @author Jonathan Dizdarevic <dizda@dizda.fr>
 */
class AddressService
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
     * Generate an HD multisig address according to the extendedPubKeys of each signers, the application_id,
     * the wallet type, and the derivation.
     *
     * @param Application $application
     * @param $isExternal
     * @param $derivation
     *
     * @throws \RuntimeException
     *
     * @return mixed|object
     */
    public function generateHDMultisigAddress(Application $application, $isExternal, $derivation)
    {
        $params = [
            'extendedPubKeys' => $application->getPubKeysSerializable(),
            'signRequired'    => $application->getKeychain()->getSignRequired(),
            'isExternal'      => $isExternal ? 'external' : 'internal',
            'derivation'      => $derivation
        ];

        // send the 3 extendedPubKeys, then the derivation, then the external chain or internal (change address)
        $process = new Process(
            sprintf(
                '%s ./node/generate_multisig_address.js \'%s\'',
                $this->nodejsPath, // we specify the path of nodejs
                $this->serializer->serialize($params, 'json')
            ),
            $this->rootPath
        );
        $process->run();

        if (!$process->isSuccessful()) {
            $this->logger->error('Can not generate HDMultisig address.', [ $process->getErrorOutput() ]);

            // Process failed
            throw new \RuntimeException($process->getErrorOutput());
        }

        return $this->serializer->deserialize($process->getOutput(), 'array', 'json');
    }
}
