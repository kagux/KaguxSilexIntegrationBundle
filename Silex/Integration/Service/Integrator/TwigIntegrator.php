<?php
namespace Kagux\SilexIntegrationBundle\Silex\Integration\Service\Integrator;
use Kagux\SilexIntegrationBundle\Silex\Integration\Service\AbstractServiceIntegrator;

class TwigIntegrator extends AbstractServiceIntegrator
{
    public function integrate($serviceId)
    {
        /** @var $twig \Twig_Environment */
        $twig = $this->container->get('twig');
        /** @var $loader  \Twig_Loader_Chain*/
        $loader = $twig->getLoader();
        if (!$loader instanceof \Twig_Loader_Chain) {
            $loader = new \Twig_Loader_Chain (array($loader));
            $twig->setLoader($loader);
        }
        $loader->addLoader($this->silex['twig.loader']);
        $twig->addGlobal('app',$this->silex);
        $twig->addGlobal('silex',$this->silex);
        /** @var $form_ext \Symfony\Bridge\Twig\Extension\FormExtension */
        $this->silex[$serviceId] = $twig;

    }
}
