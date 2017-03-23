[![Build Status](https://travis-ci.org/reliv/zf-config-factories.svg?branch=master)](https://travis-ci.org/reliv/zf-config-factories)

Zend Framework Config Factories
======
Factory classes are tedious. Factory closures have performance issues. Try this module to use factory config arrays instead. The config structure was modeled after Symfony 2 services.yml files. This package is a module for ZF2, ZF3, and Zend-Expressive.

```php
// Example of constructor injection with the service name being the same as its class name:
'service_manager' => [
    'config_factories' => [
        'App\Email\EmailService' => [
            'arguments' => [
                'Name\Of\A\Service\I\Want\To\Inject',
                'Name\Of\A\AnotherService\I\Want\To\Inject'
            ],
        ]
    ]
]
```

Example usage with all options:
```php
// in module.config.php
'service_manager' => [

    // This is a special config key that zf-config-factories reads.
    'config_factories' => [
    
        // This is the name of the service.
        'EmailTemplateApi' => [
        
            /**
             * This is the service's class name.
             * Not required if the service's name is the same as its class name.
             */
            'class' => 'App\Controller\EmailTemplateApiController',
            
            /**
             * This is an array of service names that the class's constructor takes.
             * Not required if the service's constructor takes no arguments.
             * Not compatible with the 'factory' option (see below)
             */
            'arguments' => ['Name\Of\A\Service\I\Want\To\Inject'],
            
            /** 
             * This is an array of setters to call mapped to service names to inject into each setter.
             * Not required if your service has no setters.
             */ 
            'calls' => [
                ['setFunService', ['Name\Of\Another\Service\I\Want\To\Inject']],
                ['setAnotherFunService', ['Name\Of\Another\Service\I\Want\To\Inject']]
            ],

            /**
             * Zend-Expressive style factory which is an invokable class
             * 
             * Not required if your service has no factory.
             * Not compatible with the 'arguments' option (see above)
             */ 
            'factory' => 'FunModule\Factory\Class\Name',

            /** 
             * Symfony style factory that is a service itself.
             *
             * If your service is built with a factory, register your factory its self as service
             * and call it like this. This is how factories work in Symfony.
             * Not required if your service has no factory.
             * Not compatible with the 'arguments' option (see above)
             */ 
            'factory' => [
                ['FunModule\FactoryServiceName', 'createEmailTemplateApi']
            ]
        ]
    ],
]
```

Version 4 Announcement:
- Dropped support for case-insensetive service names as ZF3 is dropping this feature. This improves performance.
- Added support for ZF3 and zend-expressive
