<?php
return [
    'service_manager' => [
        'abstract_factories' => [
            'RmFactoriesAsConfiguration\ConfigDrivenAbstractFactory'
        ],
    ],
    'controllers' => [
        'abstract_factories' => [
            'RmFactoriesAsConfiguration\ControllerConfigDrivenAbstractFactory'
        ],
    ]
];