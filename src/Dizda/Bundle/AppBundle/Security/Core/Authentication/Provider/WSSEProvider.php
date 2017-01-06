<?php

namespace Dizda\Bundle\AppBundle\Security\Core\Authentication\Provider;

use Escape\WSSEAuthenticationBundle\Security\Core\Authentication\Provider\Provider;

/**
 * This class is destinated to fix the "Future Token detected" issue.
 *
 * @see https://github.com/djoos/EscapeWSSEAuthenticationBundle/issues/48
 */
class WSSEProvider extends Provider
{
    const FUTURE_SECONDS = 3;

    protected function isTokenFromFuture($created)
    {
        return strtotime($created) > self::FUTURE_SECONDS + strtotime($this->getCurrentTime());
    }
}
