<?php

namespace Dizda\Bundle\AppBundle\Exception;

/**
 * Class InsufficientAmountException
 *
 * Throw when the inputs are lower than the outputs.
 */
class InsufficientAmountException extends \RuntimeException
{
    protected $message = 'The inputs are insufficient to spend to outputs.';
}
