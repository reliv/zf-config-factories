Inject dependencies into ZF2 services via configuration rather than factory classes.
======
Example usage with constructor injection:
```
'config_factories' => [
    'App\Controller\EmailTemplateApiController' => [
        'class' => 'App\Controller\EmailTemplateApiController',
        'arguments' => ['Name\Of\A\Service\I\Want\To\Inject'],
    ]
]
```

Example usage with setter injection:
```
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
```
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
