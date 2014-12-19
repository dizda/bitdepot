<?php

namespace Dizda\Bundle\AppBundle\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * JWTResponseListener
 *
 * @author Nicolas Cabot <n.cabot@lexik.fr>
 */
class JWTResponseListener
{
    /**
     * Add public data to the authentication response
     *
     * @param AuthenticationSuccessEvent $event
     */
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $data = $event->getData();
        $user = $event->getUser();

        if (!$user instanceof UserInterface) {
            return;
        }

        $data['data'] = [
            'username'    => $user->getUsername(),
//            'roles'    => $user->getRoles()
        ];

        $event->setData($data);
    }
}
