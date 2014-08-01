<?php

namespace Ferus\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class FerusUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
