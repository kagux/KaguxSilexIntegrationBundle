<?php
namespace Kagux\SilexIntegrationBundle\Silex\Integration\Service\Integrator;
use Kagux\SilexIntegrationBundle\Silex\Integration\Service\AbstractServiceIntegrator;

class SimpleIntegrator extends AbstractServiceIntegrator
{
    private $symfonyServiceId;

    function __construct($symfonyServiceId)
    {
        $this->symfonyServiceId=$symfonyServiceId;
    }

    public function integrate($serviceId)
    {
        $this->silex[$serviceId]=$this->container->get($this->symfonyServiceId);
    }
}
