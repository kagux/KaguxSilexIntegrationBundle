<?php

namespace Kagux\SilexIntegrationBundle\Silex;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Kagux\SilexIntegrationBundle\Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain;
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

    public function integrate()
    {
        $this->app->flush();
        $this->setDebugMode();
        if ($this->app->offsetExists('db')) $this->integrateDoctrine();
        if ($this->app->offsetExists('db.orm.em')) $this->integrateDoctrineORM();
        if ($this->app->offsetExists('twig')) $this->integrateTwig();
        $this->integrateEventDispatcher();
        return $this->app;
    }
    private function integrateDoctrine()
    {
        $this->app['db']=$this->container->get('doctrine.dbal.default_connection');
    }

    private function integrateDoctrineORM()
    {
        /** @var $silex_em  \Doctrine\ORM\EntityManager*/
        $silex_em = $this->app['db.orm.em'];
        /** @var $em  \Doctrine\ORM\EntityManager*/
        $em=$this->container->get('doctrine.orm.default_entity_manager');
        $configuration = $em->getConfiguration();
        $metadata_driver= $configuration->getMetadataDriverImpl();
        $silex_configuration=$silex_em->getConfiguration();
        $merged_driver = new MappingDriverChain;
        $merged_driver->addDriver($silex_configuration->getMetadataDriverImpl());
        $merged_driver->addDriver($metadata_driver);
        $configuration->setMetadataDriverImpl($merged_driver);
        $event_manager = $em->getEventManager();
        foreach($silex_em->getEventManager()->getListeners() as $events => $listeners){
            foreach($listeners as $listener){
                $event_manager->addEventListener($events,$listener);
            }
        }
        $this->app['db.orm.em']=$em;
    }

    private function setDebugMode()
    {
        $this->app['debug'] = in_array($this->container->get('kernel')->getEnvironment(), array('test', 'dev'));
    }

    private function integrateTwig()
    {
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

    private function integrateEventDispatcher()
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
