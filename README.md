Inject dependencies into ZF2 services via configuration rather than factory classes.
======
If most of your classes are services, this module can cut the number of classes you have in half by cutting out unnecessary factory classes. This will also speed up your app by cutting out the time it takes to instantiate factory classes.

Example usage with constructor injection:
```php
<?php // in module.config.php
'controllers' => [

    'invokables' => [
        //...
    ]

    'factories' => [
        //...
    ]

    // This is a special config key that RmFactoriesAsConfiguration looks for
    'config_factories' => [
    
        // This is the name of the service
        'App\Controller\EmailTemplateApiController' => [
        
            // This is the name of the class that the service is
            'class' => 'App\Controller\EmailTemplateApiController',
            
            // This is an array of service names that the class's constructor takes
            'arguments' => ['Name\Of\A\Service\I\Want\To\Inject'],
        ]
    ]
]
```

Example usage with setter injection:
```php
'service_manager' => [
    'config_factories' => [
        'App\Email\EmailService' => [
            'class' => 'App\Model\EmailService',
            'calls' => [
                'setFunService' => ['Name\Of\Another\Service\I\Want\To\Inject']
            ]
        ]
    ]
]
```

Example usage with both constructor injection and setter injection:
```php
'view_helpers' => [
    'config_factories' => [
        'funHelper' => [
            'class' => 'App\View\Helper\FunHelper',
            'arguments' => ['Name\Of\A\Service\I\Want\To\Inject'],
            'calls' => [
                'setFunService' => ['Name\Of\Another\Service\I\Want\To\Inject']
            ]
        ]
    ]
]
```
