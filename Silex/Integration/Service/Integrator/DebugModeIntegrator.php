<?php
namespace Kagux\SilexIntegrationBundle\Silex\Integration\Service\Integrator;
use Kagux\SilexIntegrationBundle\Silex\Integration\Service\AbstractServiceIntegrator;

class DebugModeIntegrator extends AbstractServiceIntegrator
{
    public function integrate($serviceId)
    {
        $this->silex[$serviceId] = in_array($this->container->get('kernel')->getEnvironment(), array('test', 'dev'));
    }
}
