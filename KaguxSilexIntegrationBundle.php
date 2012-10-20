<?php

namespace Kagux\SilexIntegrationBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Kagux\SilexIntegrationBundle\DependencyInjection\Compiler\ReplaceControllerResolverPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class KaguxSilexIntegrationBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ReplaceControllerResolverPass);
    }

}
