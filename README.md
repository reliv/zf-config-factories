ZF2 Config Factories
======
Install this to be able to inject dependencies into services via config rather than factory classes or closures. This is a Zend Framework 2 module.

* Config factories are faster than factory classes because because your app doesn't need to instantiate a factory for each service at runtime.
* Config factories are MUCH faster than factory closures because ZF2 parses all unused factory closures every request. We have seen ZF2 apps with 200+ factory closures where the closures caused major performance issues.

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
                'setFunService' => ['Name\Of\Another\Service\I\Want\To\Inject']
            ]
        ]
    ],
]
```
