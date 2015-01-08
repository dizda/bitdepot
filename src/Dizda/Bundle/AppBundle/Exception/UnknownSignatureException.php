<?php

namespace Dizda\Bundle\AppBundle\Exception;

/**
 * Class UnknownSignatureException
 *
 * When the public key of the signature doesn't exist
 */
class UnknownSignatureException extends \RuntimeException
{
    protected $message = 'The signature is unknown.';
}
