<?php

namespace Kagux\SilexIntegrationBundle\Silex\Integration\Service;

class Factory
{
    private $id;
    /**
     * @var \Silex\Application
     */
    private $silex;


    function __construct(\Silex\Application $silex, $id)
    {
        $this->id = $id;
        $this->silex = $silex;
    }

    public function create()
    {
        return $this->silex[$this->id];
    }


}
