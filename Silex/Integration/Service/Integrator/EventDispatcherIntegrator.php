<?php
namespace Kagux\SilexIntegrationBundle\Silex\Integration\Service\Integrator;
use Kagux\SilexIntegrationBundle\Silex\Integration\Service\AbstractServiceIntegrator;
use Symfony\Component\HttpKernel\KernelEvents;

class EventDispatcherIntegrator extends AbstractServiceIntegrator
{
    public function integrate($serviceId)
    {
//        $this->filterSilexListeners($serviceId, KernelEvents::REQUEST);
        $this->addSilexListeners($serviceId, KernelEvents::REQUEST);
        $this->addSilexListeners($serviceId, KernelEvents::RESPONSE);
        $this->silex[$serviceId] = $this->container->get('event_dispatcher');
    }

    private function filterSilexListeners($serviceId, $eventName)
    {
        foreach($this->silex[$serviceId]->getListeners($eventName) as $listener){
            if (!$listener instanceof \Closure){
                $this->silex[$serviceId]->removeListener($eventName, $listener);
            }
        }
    }

    private function addSilexListeners($serviceId, $eventName)
    {
        foreach($this->silex[$serviceId]->getListeners($eventName) as $listener){
            if ($listener instanceof \Closure){
                $this->container->get('event_dispatcher')->addListener($eventName, $listener);
            }
        }

    }
}
