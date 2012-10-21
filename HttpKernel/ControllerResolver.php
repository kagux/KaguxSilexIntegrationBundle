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
        if ($request->get('_controller') instanceof \Closure){
            return  $this->app['resolver'];
        }
        elseif($request->get('_controller')=='silex'){
            $this->app->flush();
            /** @var $route \Symfony\Component\Routing\Route */
            $route = $request->get('_route');
            /** @var $routes \Symfony\Component\Routing\RouteCollection */
            $routes = $this->app['routes'];
            $controller = $routes->get($route)->getDefault('_controller');
            $request->attributes->set('_controller', $controller);
            return  $this->app['resolver'];
        }
        else{
             return $this->original_controller_resolver;
        }
    }
}
