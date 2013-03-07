<?php
namespace Kagux\SilexIntegrationBundle\Twig\Form\Engine\Resources;

use Silex\Application;

class Resolver
{
    private $app;
    private $default_themes;

    public function __construct(Application $app, array $default_themes)
    {
        $this->app=$app;
        $this->default_themes=$default_themes;
    }

    public function resolve()
    {
        return $this->silexTwigFormTemplates() + $this->default_themes;
    }

    /**
     * @return mixed
     */
    private function silexTwigFormTemplates()
    {
        return isset($this->app['twig.form.templates'])? $this->app['twig.form.templates'] : array();
    }

}
