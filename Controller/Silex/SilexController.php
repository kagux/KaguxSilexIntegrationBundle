<?php

namespace Kagux\SilexIntegrationBundle\Controller\Silex;

class SilexController
{
    private $closure;

    public function __construct(\Closure $closure)
    {
        $this->closure=$closure;
    }

    public function getController()
    {
       return $this->closure;
    }

    static function __set_state($an_array)
    {
        return 'Yo';
    }


}
