<?php

namespace Dizda\Bundle\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class DizdaUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
