<?php

namespace Dizda\Bundle\AppBundle\EventListener;

use Pagerfanta\Pagerfanta;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * class RangeHeaderListener
 *
 * Listen to "Range" headers to perform a pagination
 * cf. https://github.com/begriffs/angular-paginate-anything
 *
 * @author Jonathan Dizdarevic <dizda@dizda.fr>
 * @since  05/09/2014 10:11
 */
class RangeHeaderListener
{
    /**
     * @var string
     */
    protected $contentRange;

    /**
     * Adding "offset" and "limit" to request parameters when "Range" header is detected
     *
     * @param GetResponseEvent         $event
     * @param string                   $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function onKernelRequest(GetResponseEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        if (!$event->isMasterRequest()) {
            // don't do anything if it's not the master request
            return;
        }

        if ('json' !== $event->getRequest()->getRequestFormat() || !$event->getRequest()->headers->get('range')) {
            return;
        }

        list($offset, $limit) = explode('-', $event->getRequest()->headers->get('range'));

        $event->getRequest()->query->add([
            'maxPerPage'  => (int) ($limit - $offset) + 1,
            'currentPage' => (int) $offset / (($limit - $offset) + 1) + 1 // $offset / maxPerPage
        ]);

        // Modify the return of the controller
        $dispatcher->addListener(KernelEvents::VIEW, [$this, 'onKernelView'], 100);

        // Modify the response to inject expected headers
        $dispatcher->addListener(KernelEvents::RESPONSE, [$this, 'onKernelResponse']);
    }

    /**
     * Replace the return of the controller,
     *      - The 'range' property will be moved into headers
     *      - The 'response' property will be use as the controller result
     *
     * @param GetResponseForControllerResultEvent $event
     */
    public function onKernelView(GetResponseForControllerResultEvent $event)
    {
        if (!$event->getControllerResult() instanceof Pagerfanta) {
            return;
        }

        /**
         * @var Pagerfanta
         */
        $pagerfanta = $event->getControllerResult();

        // Will be used in the onKernelResponse
        $this->contentRange = sprintf(
            '%d-%d/%d',
            $pagerfanta->getCurrentPageOffsetStart(),
            $pagerfanta->getCurrentPageOffsetEnd(),
            $pagerfanta->count()
        );

        $event->setControllerResult(iterator_to_array($pagerfanta->getCurrentPageResults()));
    }

    /**
     * Attach expected headers by Angular-Paginate-Anything OR any RFC2616 compliant
     *
     * @param FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $event->getResponse()->headers->add([
            'Accept-Ranges' => 'items',
            'Range-Unit'    => 'items',
            'Content-Range' => $this->contentRange
        ]);
    }
}
