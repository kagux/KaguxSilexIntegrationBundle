<?php
namespace Kagux\SilexIntegrationBundle\HttpKernel;

use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class ControllerResolver implements ControllerResolverInterface
{
    private $original_controller_resolver;
    private $app;

    public function __construct(ControllerResolverInterface $original_controller_resolver, Application $app)
    {
        $this->original_controller_resolver=$original_controller_resolver;
        $this->app=$app;
    }

    public function getController(Request $request)
    {
        return $this->getResolver($request)->getController($request);
    }


    public function getArguments(Request $request, $controller)
    {
        return $this->getResolver($request)->getArguments($request,$controller);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpKernel\Controller\ControllerResolverInterface
     */
    private function getResolver(Request $request)
    {
        return ($request->get('_controller') instanceof \Closure)?$this->app['resolver']: $this->original_controller_resolver;
    }
}
