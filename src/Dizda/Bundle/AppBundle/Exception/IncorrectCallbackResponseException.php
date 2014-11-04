<?php

namespace Dizda\Bundle\AppBundle\Exception;

/**
 * Class IncorrectCallbackResponseException
 *
 * Throw when the callback response is not HTTP code expected.
 */
class IncorrectCallbackResponseException extends \RuntimeException
{
    protected $message = 'The callback response is incorrect.';
}
