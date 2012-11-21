<?php
namespace Kagux\SilexIntegrationBundle\Silex\Integration\Service\Integrator;
use Kagux\SilexIntegrationBundle\Silex\Integration\Service\AbstractServiceIntegrator;

class DoctrineORMIntegrator extends AbstractServiceIntegrator
{
    public function integrate($serviceId)
    {
        $cache = $this->container->get('doctrine.orm.default_entity_manager')->getConfiguration()->getResultCacheImpl();
        $this->container->get('doctrine.dbal.default_connection')->getConfiguration()->setResultCacheImpl($cache);
        /** @var $silex_em  \Doctrine\ORM\EntityManager*/
        $silex_em = $this->silex[$serviceId];
        /** @var $em  \Doctrine\ORM\EntityManager*/
        $em=$this->container->get('doctrine.orm.default_entity_manager');
        $event_manager = $em->getEventManager();
        foreach($silex_em->getEventManager()->getListeners() as $events => $listeners){
            foreach($listeners as $listener){
                $event_manager->addEventListener($events,$listener);
            }
        }
        $this->silex[$serviceId]=$em;
    }
}
