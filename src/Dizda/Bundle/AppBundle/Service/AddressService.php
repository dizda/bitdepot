<?php

namespace Dizda\Bundle\AppBundle\Service;

use Dizda\Bundle\AppBundle\Entity\Application;
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

    public function __construct(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }


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

        var_dump($process->getOutput());
//        if ($process->isTerminated()) {
//            $option->setNodeProcess([
//                'stdout' => $process->getOutput(),
//                'stderr' => $process->getErrorOutput(),
//                'code'   => $process->getExitCode()
//            ]);
//            $option->finish($process);
//
//            $this->om->flush();
//        }
//
//        if (!$process->isSuccessful()) {
//            if (77 === $process->getExitCode()) {
//                /**
//                 * The case when agent got a new signature that is different from the one submitted
//                 */
//                throw new SignatureMismatchException('The fresh signature mismatch.');
//            }
//            if (78 === $process->getExitCode()) {
//                /**
//                 * The case when agent found that the lot is already optioned
//                 */
//                throw new OptionUnavailableException('The lot is already optioned.');
//            }
//
//            // Process failed
//            throw new \RuntimeException($process->getErrorOutput());
//        }

    }
}
