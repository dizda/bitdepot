<?php

namespace Dizda\Bundle\AppBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Class RequestListener
 *
 * @author Jonathan Dizdarevic <dizda@dizda.fr>
 */
class RequestListener
{
    /**
     * @var \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage
     */
    private $tokenStorage;

    /**
     * @param TokenStorage $tokenStorage
     */
    public function __construct(TokenStorage $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * The goal of this method is to add the "application_id" to the request attributes
     * when an WSSE Application make an API Request.
     * Because the application-client doesn't have to know the "application_id".
     *
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        if ('json' !== $event->getRequest()->getRequestFormat() || $this->tokenStorage->getToken() === null || !$this->tokenStorage->getToken()->isAuthenticated()) {
            return;
        }

        // Only match when the user is logged in through an application (WSSE)
        if (get_class($this->tokenStorage->getToken()->getUser()) !== 'Dizda\Bundle\AppBundle\Entity\Application') {
            return;
        }

//        if (!$this->tokenStorage->getToken()->getUser()->hasRole('ROLE_WSSE')) {
//            return;
//        }

        // Setting automatically the application id
        if ($event->getRequest()->isMethod('POST')) {
            $event->getRequest()->request->add([
                // Get the application ID linked to the application used
                'application_id' => $this->tokenStorage->getToken()->getUser()->getId()
            ]);

            $this->tokenStorage->getToken()->getUser()->addRole(sprintf(
                'APP_ACCESS_%d', $this->tokenStorage->getToken()->getUser()->getId()
            ));
        }
    }
}
