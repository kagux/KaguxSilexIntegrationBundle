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
        $this->app->flush();
        $this->integrateEventDispatcher();
        $this->integrateTwig();
        return $this->app;
    }

    public function integrateTwig()
    {
        if ($this->app->offsetExists('twig')) {
            /** @var $twig \Twig_Environment */
            $twig = $this->container->get('twig');
            /** @var $loader  \Twig_Loader_Chain*/
            $loader = $twig->getLoader();
            if (!$loader instanceof \Twig_Loader_Chain) {
                $loader = new \Twig_Loader_Chain (array($loader));
                $twig->setLoader($loader);
            }
            $loader->addLoader($this->app['twig.loader']);
            $this->app['twig'] = $twig;

        }
    }

    public function integrateEventDispatcher()
    {
        $event_dispatcher = $this->container->get('event_dispatcher');
        $event_dispatcher->addSubscriber($this->app);
        $this->addSilexListeners(SilexEvents::AFTER);
        $this->addSilexListeners(SilexEvents::BEFORE);
        $this->addSilexListeners(SilexEvents::ERROR);
        $this->addSilexListeners(SilexEvents::FINISH);
        $this->app['dispatcher'] = $event_dispatcher;
    }

    private function addSilexListeners($eventName)
    {
        foreach($this->app['dispatcher']->getListeners($eventName) as $listener){
            $this->container->get('event_dispatcher')->addListener($eventName, $listener);
        }

    }

}
