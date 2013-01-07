<?php
namespace Kagux\SilexIntegrationBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Kagux\SilexIntegrationBundle\Silex\Integration\Service\ServiceIntegrationMode;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AddAutoIntegratorsPass  implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if(!$container->getParameter('silex.app.enabled')) return;
        $pool = $container->getDefinition('silex.integration.service.pool');
        foreach ($container->findTaggedServiceIds('silex.auto_integrator') as $id => $tags) {
            $integrator = new Definition('Kagux\SilexIntegrationBundle\Silex\Integration\Service\Integrator\SimpleIntegrator', array($id));
            $integrator->addMethodCall('setContainer',array(new Reference('service_container')));
            $integrator->addMethodCall('setSilex',array(new Reference($container->getParameter('silex.app.service'))));
            $pool->addMethodCall('addServiceIntegrator', array($integrator, $id, ServiceIntegrationMode::CREATE_IF_NO_SERVICE));
        }
    }

}
