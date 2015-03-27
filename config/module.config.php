<?php
return array(
    'service_manager' => array(
        'abstract_factories' => array(
            'Rm\FactoriesAsConfiguration\ServiceFactory'
        ),
    ),
    'controllers' => array(
        'abstract_factories' => array(
            'Rm\FactoriesAsConfiguration\ControllerFactory'
        ),
    ),
    'view_helpers' => array(
        'abstract_factories' => array(
            'Rm\FactoriesAsConfiguration\ViewHelperFactory'
        ),
    ),
    'controller_plugins' => array(
        'abstract_factories' => array(
            'Rm\FactoriesAsConfiguration\ControllerPluginFactory'
        ),
    )
);
