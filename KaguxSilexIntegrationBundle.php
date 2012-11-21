<?php

namespace Kagux\SilexIntegrationBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Kagux\SilexIntegrationBundle\DependencyInjection\Compiler\TwigFormIntegrationPass;
use Kagux\SilexIntegrationBundle\DependencyInjection\Compiler\SilexAppServiceIntegrationPass;
use Kagux\SilexIntegrationBundle\DependencyInjection\Compiler\DoctrineMetadataIntegrationPass;
use Kagux\SilexIntegrationBundle\DependencyInjection\Compiler\ControllerResolveIntegrationPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class KaguxSilexIntegrationBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ControllerResolveIntegrationPass);
        $container->addCompilerPass(new DoctrineMetadataIntegrationPass());
        $container->addCompilerPass(new SilexAppServiceIntegrationPass());
        $container->addCompilerPass(new TwigFormIntegrationPass());
    }

}
