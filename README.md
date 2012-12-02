KaguxSilexIntegrationBundle
===========================

Introduction

Silex is a great framework for small application. Overall simplicity, elegancy
 of routing and power of underlying symfony components are playing key roles here.
  However, what if you started a project with a few features in
mind, but it has grown a lot more since then? Services declarations now take
 a few hundred lines. Controllers are bloated even though you separate them into mounted controllers.
 You find yourself reimplementing providers for most symfony2 components and bundles.  And now
 you wish you had started with symfony2 all along.
 One possible solution is to completely port application to symfony2. But if you don't want to put on hold
 development of your application, this route is rather difficult and time consuming.
 This bundle solves the problem by seamlessly integrating Silex application into Symfony2.
 Here's what it does:
    - silex routes are now symfony2 routes
    - silex services are now symfony2 services and are available via container
    - silex services are replaces with corresponding symfony2 services (twig, doctrine dbal, doctrine orm, forms)
    - easy integration of custom services

 Installation






