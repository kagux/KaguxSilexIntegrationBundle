<?php
namespace Kagux\SilexIntegrationBundle\Silex\Integration\Service\Pool;

use Kagux\SilexIntegrationBundle\Silex\Integration\Service\ServiceIntegratorInterface;
use Kagux\SilexIntegrationBundle\Silex\Integration\Service\ServiceIntegrationMode;
use Kagux\SilexIntegrationBundle\Silex\SilexAware;

class ServicePool extends SilexAware
{
    /**
     * @var ServiceIntegratorInterface[]
     */
    protected $servicePool=array();
    protected $serviceModes=array();

    public function addServiceIntegrator(ServiceIntegratorInterface $integrator, $silexServiceId, $mode, $priority = 0)
    {
        if (!isset($this->servicePool[$priority])) $this->servicePool[$priority]=array();
        $this->servicePool[$priority][$silexServiceId]=$integrator;
        $this->serviceModes[$silexServiceId]=$mode;
    }

    public function integrate()
    {
        foreach($this->servicePool as $services){
            $this->integrateServices($services);
        }
    }

    private function integrateServices($services)
    {
        foreach ($services as $silexServiceId => $integrator)
            /** @var $integrator ServiceIntegratorInterface */
            if ($this->serviceShouldBeIntegrated($silexServiceId)) {
                $integrator->integrate();
            }
    }

    private function serviceShouldBeIntegrated($serviceId)
    {
        return $this->serviceModes[$serviceId] == ServiceIntegrationMode::CREATE_IF_NO_SERVICE || $this->silex->offsetExists($serviceId);
    }

}

