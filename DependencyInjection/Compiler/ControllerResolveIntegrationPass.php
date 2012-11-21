<?php
namespace Kagux\SilexIntegrationBundle\DependencyInjection\Compiler;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ControllerResolveIntegrationPass  implements CompilerPassInterface
{

    public function process(ContainerBuilder $container)
    {
        if(!$container->getParameter('silex.app.enabled')) return;
        $container->setDefinition('original.controller_resolver', $container->findDefinition('controller_resolver'));
        $container->setAlias('controller_resolver',new Alias('silex.controller_resolver'),false);
    }
}
