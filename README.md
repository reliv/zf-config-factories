Inject dependencies into ZF2 services via configuration rather than factory classes.
======
Example usage with constructor injection:
```php
'config_factories' => [

    // This is the name of the service
    'App\Controller\EmailTemplateApiController' => [
    
        // This is the name of the class that the service is
        'class' => 'App\Controller\EmailTemplateApiController',
        
        // This is an array of service names that the class's constructor takes
        'arguments' => ['Name\Of\A\Service\I\Want\To\Inject'],
    ]
]
```

Example usage with setter injection:
```php
'config_factories' => [
    'App\Controller\EmailTemplateApiController' => [
        'class' => 'App\Controller\EmailTemplateApiController',
        'calls' => [
            'setFunService' => ['Name\Of\Another\Service\I\Want\To\Inject']
        ]
    ]
]
```

Example usage with constructor injection and setter injection:
```php
'config_factories' => [
    'App\Controller\EmailTemplateApiController' => [
        'class' => 'App\Controller\EmailTemplateApiController',
        'arguments' => ['Name\Of\A\Service\I\Want\To\Inject'],
        'calls' => [
            'setFunService' => ['Name\Of\Another\Service\I\Want\To\Inject']
        ]
    ]
]
```
