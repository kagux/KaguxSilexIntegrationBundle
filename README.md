KaguxSilexIntegrationBundle
===========================

# Introduction

Silex is a great framework for small application. 
Overall simplicity, elegancy of routing and power of underlying symfony components are playing key roles here.

However, what if you started a project with a few features in mind, but it has grown a lot more since then? 
Services declarations now take a few hundred lines. 
Controllers are bloated even though you separate them into mounted controllers.
You find yourself reimplementing providers for most symfony2 components and bundles. 
And now you wish you had started with symfony2 all along.

One of the possible solutions is to completely port application to symfony2. 
But if you don't want to put on hold development of your application, this route is rather difficult and time consuming.
 
This bundle solves the problem by seamlessly integrating Silex application into Symfony2.

Here's what you get:

 + symfony 2.1 dev-master compatibility
 + silex routes are now symfony2 routes
  + symfony2 routes have prioroty over silex routes
  + gradually migrate silex controllers to symfony2 by replacing routes
 + silex services are now symfony2 services and are available via container
  + use services defined within silex application in symfony2 bundles
  + gradually migrate services to symfony2 container format
 + silex services are replaced with corresponding symfony2 services (twig, doctrine dbal, doctrine orm, forms)
  + configure common services through symfony2 config files
  + silex and symfony2 share same db connection, twig templates, forms and so on
 + easy integration of custom services
  + access symfony2 service in silex simply by tagging it `silex.auto_integrator`
  + write custom integration class for complex scenarios

# Installation
 + Add Symfony 2.1 dependencies to Silex application `composer.json` 
 + Add `"kagux/symfony2-silex-integration-bundle": "dev-master"` to `composer.json` 
  + Working composer requirements list example
  
``` json
        "require": { 
         "php": ">=5.3.3",
         "silex/silex": "1.0.*",
         "symfony/symfony": "2.1.*",        
         "doctrine/orm": ">=2.2.3,<2.4-dev",        
         "doctrine/doctrine-bundle": "1.0.*",
         "twig/extensions": "1.0.*",        
         "symfony/assetic-bundle": "2.1.*",        
         "symfony/swiftmailer-bundle": "2.1.*",        
         "symfony/monolog-bundle": "2.1.*",        
         "sensio/distribution-bundle": "2.1.*",        
         "sensio/framework-extra-bundle": "2.1.*",        
         "sensio/generator-bundle": "2.1.*",
         "jms/security-extra-bundle": "1.2.*",
         "jms/di-extra-bundle": "1.1.*",
         "kagux/symfony2-silex-integration-bundle": "dev-master"
        }
```  
 +  Run `php composer.php update`
 +  Register KaguxSilexIntegrationBundle in `/app/AppKernel.php`

``` php
    public function registerBundles()
    {
        $bundles = array(
            ...
            new Kagux\SilexIntegrationBundle\KaguxSilexIntegrationBundle()
        );
        
        ....

    } 
```

 +  Create new bundle that will contain code related to integration
   + For the sake of example, lets name it `KaguxLegacyAppBundle`
 +  Create factory class to wrap your Silex application 

``` php
namespace Kagux\LegacyAppBundle\Application;

use Silex\Application;

class ApplicationFactory
{
    /**
     * @throws \Exception
     * @return \Silex\Application
     */
    public function create()
    {
        $app=new Application;
        $app->get('/silex', function() use ($app) {
            return 'Hello, world!';
        });
        return $app;
    }

}
```
   + Define a service for your Silex application
   
``` yaml   
parameters:
 silex.application.class:  Silex\Application
 silex.application.factory.class: Kagux\LegacyAppBundle\Application\ApplicationFactory

services:
  silex.application.factory:
    class: %silex.application.factory.class%

  legacy.silex.application:
    class: %silex.application.class%
    factory_service: silex.application.factory
    factory_method: create
```

  + Add to your `config.yml`
  
``` yaml  
  kagux_silex_integration:
   app_service: legacy.silex.application
```
  + Create routing file in your bundle `/src/Kagux/LegacyAppBundle/Resources/config/routing.yml`
  
``` yaml   
  legacy_silex_application:
    resource: .
    type: silex
```

  + Add to `/app/config/routing.yml`

``` yaml  
kagux_legacy_app:
    resource: "@KaguxLegacyAppBundle/Resources/config/routing.yml"
    prefix:   /
```

That should be it. At this point, if you browse `your_site.com/silex` you will see 'Hello, world!'.

# TODO
 + Update to  symfony 2.2
 + Create specs
 
# Contrubuting 
  + Fork 
  + Create changes in separate branch
  + Squash your changes into master
  + Create pull request
  
  
  






