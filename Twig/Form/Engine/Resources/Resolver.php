<?php
namespace Kagux\SilexIntegrationBundle\Twig\Form\Engine\Resources;

class Resolver
{
    private $app;
    private $default_themes;

    public function __construct(\Silex\Application $app, array $default_themes)
    {
        $this->app=$app;
        $this->default_themes=$default_themes;
    }

    public function resolve()
    {
        return $this->app['twig.form.templates'] + $this->default_themes;
    }

}
