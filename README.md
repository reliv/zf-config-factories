Inject dependencies into ZF2 services via configuration rather than factory classes.
======
This module has been in-use in production apps almost since the ZF 2.3 release. Developers seem to like its simplicity versues factory classes.

*This method is faster than factory classes because because your apps doesn't need to instantiate a factory for each service at runtime.
*This method is MUCH faster than factory closures in large apps because all factory closures are parsed at runtime, even when they are not used. Factories closures should not be used in large ZF apps due to their performance issues.

Example of constructor injection with the service name being the same as its class name:
```php
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
'controllers' => [

    // This is a special config key that zf2-factories-as-configuration reads.
    'config_factories' => [
    
        // This is the name of the service.
        'EmailTemplateApiController' => [
        
            /**
             * This is the service's  class name.
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
                'setFunService' => ['Name\Of\Another\Service\I\Want\To\Inject']
            ]
        ]
    ],
]
```
