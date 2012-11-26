<?php
namespace Kagux\SilexIntegrationBundle\Silex\Integration\Service\Integrator;
use Symfony\Component\HttpFoundation\Request;

class RequestIntegrator
{

    /**
     * @var \Silex\Application
     */
    protected $silex;
    /**
     * @var Request
     */
    protected $request;


    function __construct($request, $silex)
    {
        $this->request = $request;
        $this->silex = $silex;
    }

    public function integrate()
    {
        $this->silex['request']=$this->request;

    }

}
