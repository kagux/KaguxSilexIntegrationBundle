<?php
namespace Kagux\SilexIntegrationBundle\DependencyInjection\Compiler;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ReplaceControllerResolverPass  implements CompilerPassInterface
{

    public function process(ContainerBuilder $container)
    {
        $container->setDefinition('original.controller_resolver', $container->findDefinition('controller_resolver'));
        $container->setAlias('controller_resolver',new Alias('silex.controller_resolver'),false);
    }
}
