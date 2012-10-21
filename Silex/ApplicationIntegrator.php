<?php

namespace Kagux\SilexIntegrationBundle\Silex;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Silex\SilexEvents;
use Silex\Application;

class ApplicationIntegrator
{
    private  $container;
    private  $app;

    public function __construct(ContainerInterface $container, Application $app)
    {
        $this->container=$container;
        $this->app=$app;
    }

    public function getIntegratedApplication()
    {
        /** @var $event_dispatcher \Symfony\Component\EventDispatcher\EventDispatcher */
        $this->app->flush();
        $event_dispatcher =$this->container->get('event_dispatcher');
        $event_dispatcher->addSubscriber($this->app);
        $this->addSilexListeners(SilexEvents::AFTER);
        $this->addSilexListeners(SilexEvents::BEFORE);
        $this->addSilexListeners(SilexEvents::ERROR);
        $this->addSilexListeners(SilexEvents::FINISH);
        $this->app['dispatcher']=$event_dispatcher;
        return $this->app;
    }

    private function addSilexListeners($eventName)
    {
        foreach($this->app['dispatcher']->getListeners($eventName) as $listener){
            $this->container->get('event_dispatcher')->addListener($eventName, $listener);
        }

    }

}
