<?php
// src/Sdz/UserBundle/synry63UserBundle.php

namespace synry63\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class synry63UserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}

    