<?php

namespace Dizda\Bundle\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * AuthenticationController
 */
class AuthenticationController extends Controller
{
    /**
     * Recover updated datas of the user
     *
     * @return JsonResponse
     */
    public function pingAction()
    {
        return $this->getUser();
    }
}
