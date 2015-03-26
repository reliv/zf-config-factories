common
======
Example usage in config:

'config_factories' => [
            'App\Controller\EmailTemplateApiController' => [
                'class' => 'App\Controller\EmailTemplateApiController',
                'arguments' => ['Doctrine\ORM\EntityManager'],
                'calls' => [
                    'setCurrentPwsService' => ['Pws\Service\CurrentPws']
                ]
            ]
        ]
    ]
]