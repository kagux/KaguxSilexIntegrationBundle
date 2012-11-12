<?php

namespace Kagux\SilexIntegrationBundle\Silex;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
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

    public function integrate(GetResponseEvent $event)
    {
        if($this->container->get('request')->get('_controller') != 'silex') return;
        $this->setDebugMode();
        if ($this->app->offsetExists('db')) $this->integrateDoctrine();
        if ($this->app->offsetExists('db.orm.em')) $this->integrateDoctrineORM();
        if ($this->app->offsetExists('twig')) $this->integrateTwig();
        if ($this->app->offsetExists('form.factory')) $this->integrateForm();
        if ($this->app->offsetExists('session')) $this->integrateSession();
        if ($this->app->offsetExists('request')) $this->integrateRequest();
        if ($this->app->offsetExists('mailer')) $this->integrateMailer();
        $this->integrateEventDispatcher($event);
    }

    private function integrateMailer()
    {
        $this->app['mailer']=$this->container->get('mailer');
    }

    private function integrateRequest()
    {
        $this->app['request']=$this->container->get('request');
    }

    private function integrateSession()
    {
        $this->app['session']=$this->container->get('session');
    }

    private function integrateForm()
    {
        $this->app['form.factory']=$this->container->get('form.factory');
    }

    private function integrateDoctrine()
    {
        $cache = $this->container->get('doctrine.orm.default_entity_manager')->getConfiguration()->getResultCacheImpl();
        $this->container->get('doctrine.dbal.default_connection')->getConfiguration()->setResultCacheImpl($cache);
        $this->app['db']=$this->container->get('doctrine.dbal.default_connection');
    }

    private function integrateDoctrineORM()
    {
        /** @var $silex_em  \Doctrine\ORM\EntityManager*/
        $silex_em = $this->app['db.orm.em'];
        /** @var $em  \Doctrine\ORM\EntityManager*/
        $em=$this->container->get('doctrine.orm.default_entity_manager');
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
        $twig->addGlobal('app',$this->app);
        /** @var $form_ext \Symfony\Bridge\Twig\Extension\FormExtension */
        $this->app['twig'] = $twig;

    }


    private function integrateEventDispatcher(GetResponseEvent $event)
    {
        $this->filterSilexListeners(KernelEvents::REQUEST);
        $this->app['dispatcher']->dispatch(KernelEvents::REQUEST, $event);
        $this->addSilexListeners(KernelEvents::RESPONSE);
        $this->app['dispatcher'] = $this->container->get('event_dispatcher');
    }

    private function filterSilexListeners($eventName)
    {
        foreach($this->app['dispatcher']->getListeners($eventName) as $listener){
            if (!$listener instanceof \Closure){
                $this->app['dispatcher']->removeListener($eventName, $listener);
            }
        }
    }

    private function addSilexListeners($eventName)
    {
        foreach($this->app['dispatcher']->getListeners($eventName) as $listener){
            if ($listener instanceof \Closure){
                $this->container->get('event_dispatcher')->addListener($eventName, $listener);
            }
        }

    }

}
