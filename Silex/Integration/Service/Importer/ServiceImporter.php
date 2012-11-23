<?php
namespace Kagux\SilexIntegrationBundle\Silex\Integration\Service\Importer;

use Silex\Application;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ServiceImporter
{

    /**
     * @var Application
     */
    protected $silex;
    /**
     * @var ContainerInterface
     */
    protected $container;

    function __construct(ContainerInterface $container, Application $silex)
    {
        $this->container = $container;
        $this->silex = $silex;
    }

    public function import()
    {
        foreach($this->silex->keys() as $serviceId ){
           if($serviceId == 'autoloader' || $this->container->has($serviceId)) continue;
           $this->container->set($serviceId,$this->silex->offsetGet($serviceId));
        }
    }

}
