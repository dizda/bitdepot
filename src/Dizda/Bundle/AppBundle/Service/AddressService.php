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
     * @param Serializer $serializer
     * @param Logger     $logger
     */
    public function __construct(Serializer $serializer, Logger $logger)
    {
        $this->serializer = $serializer;
        $this->logger     = $logger;
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

        // send the 3 extendedPubKeys, then the derivation, the the external or not
        $process = new Process(
            sprintf('node ./node/generate_multisig_address.js \'%s\'', $this->serializer->serialize($params, 'json')),
            null
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
