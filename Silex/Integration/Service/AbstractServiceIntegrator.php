<?php

namespace Kagux\SilexIntegrationBundle\Silex\Integration\Service;

use Kagux\SilexIntegrationBundle\Silex\SilexAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

abstract class AbstractServiceIntegrator implements ContainerAwareInterface, SilexAwareInterface, ServiceIntegratorInterface
{
    /**
     * @var \Silex\Application
     */
    protected $silex;
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    public function setSilex(\Silex\Application $silex)
    {
        $this->silex=$silex;
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container=$container;
    }

    abstract public  function integrate($serviceId);

    public function getMode()
    {
        return ServiceIntegrationMode::SKIP_IF_NO_SERVICE;
    }
}
