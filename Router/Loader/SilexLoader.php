<?php
namespace Kagux\SilexIntegrationBundle\Router\Loader;

use Symfony\Component\Config\Loader\Loader;
use Kagux\SilexIntegrationBundle\Controller\Silex\SilexController;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Silex\Application;

class SilexLoader  extends Loader
{
    private $app;

    public function __construct(Application $app)
    {
        $this->app=$app;
    }


    public function supports($resource, $type = null)
    {
        return $type == 'silex';
    }

    public function load($resource, $type = null)
    {
        $this->app->flush();
        return $this->integrateRoutes($this->app['routes']);
    }

    private function integrateRoutes(RouteCollection $routes)
    {
        $integratedRoutes = new RouteCollection;

        foreach($routes as $name=>$route){
            /** @var $route \Symfony\Component\Routing\Route */
            if ($route instanceof RouteCollection){
                /** @var $route \Symfony\Component\Routing\RouteCollection */
                $integratedRoutes->addCollection($this->integrateRoutes($route));
            }
            else {
                $integratedRoutes->add($name, $this->integrateRoute($route));
            }
        }
        return $integratedRoutes;
    }

    private function integrateRoute(Route $route)
    {
        $integratedRoute = clone $route;
        $integratedRoute->setDefault('_controller', 'silex');
        return $integratedRoute;
    }


}
