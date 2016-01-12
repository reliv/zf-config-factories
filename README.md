[![Build Status](https://travis-ci.org/reliv/zf-config-factories.svg?branch=master)](https://travis-ci.org/reliv/zf-config-factories)

Zend Framework Config Factories
======
Factory classes are tedious. Factory closures have performance issues. Try this module to use factory config arrays instead. This module is built for high performance and ease of use.

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
             * If your service is built with a factory, register your factory its self as service
             * and call it like this. This is how factories work in Symfony.
             * Not required if your service has no factory.
             */ 
            'factory' => [
                ['FunModule\FactoryServiceName', 'createEmailTemplateApi']
            ]
        ]
    ],
]
```
