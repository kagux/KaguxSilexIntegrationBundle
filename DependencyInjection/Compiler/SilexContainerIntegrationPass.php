<?php
namespace Kagux\SilexIntegrationBundle\DependencyInjection\Compiler;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SilexContainerIntegrationPass  implements CompilerPassInterface
{

    public function process(ContainerBuilder $container)
    {
        if(!$container->getParameter('silex.app.enabled')) return;
        $silex = $container->get($container->getParameter('silex.app.service'));
        foreach($silex->keys() as $serviceId ){
            if($container->has($serviceId)) continue;
            $factoryId = "silex.$serviceId.service.factory";

            $this->createServiceFactory($container, $serviceId,  $factoryId);

            $this->createService($container, $serviceId, $factoryId);
        }
    }

    private function createService(ContainerBuilder $container, $serviceId, $factoryId)
    {
        $silexServiceDefinition = new Definition();
        $silexServiceDefinition->setClass('SilexServiceClass');
        $silexServiceDefinition->setFactoryService($factoryId);
        $silexServiceDefinition->setFactoryMethod('create');
        $container->setDefinition($serviceId, $silexServiceDefinition);
    }

    private function createServiceFactory(ContainerBuilder $container, $serviceId, $factoryId)
    {
        $silexFactoryServiceDefinition = new Definition('Kagux\SilexIntegrationBundle\Silex\Integration\Service\Factory');
        $silexFactoryServiceDefinition->addArgument(new Reference('silex.app'));
        $silexFactoryServiceDefinition->addArgument($serviceId);
        $container->setDefinition($factoryId, $silexFactoryServiceDefinition);
    }
}
