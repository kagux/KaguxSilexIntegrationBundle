<?php
namespace Kagux\SilexIntegrationBundle\Silex\Route;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class Converter
{
    private $app;
    private $request;

    public function __construct(Application $app, Request $request)
    {
        $this->app=$app;
        $this->request=$request;
    }

    public function convert()
    {
        if($this->request->get('_controller')!='silex') return;
        $this->app->flush();
        /** @var $routes \Symfony\Component\Routing\RouteCollection */
        $routes = $this->app['routes'];
        $silex_route = $routes->get($this->request->get('_route'));
        $this->request->attributes->set('_controller', $silex_route->getDefault('_controller'));
    }

}
