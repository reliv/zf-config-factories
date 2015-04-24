<?php
return array(
    'service_manager' => array(
        'abstract_factories' => array(
            'Reliv\FactoriesAsConfiguration\ServiceFactory'
        ),
    ),
    'controllers' => array(
        'abstract_factories' => array(
            'Reliv\FactoriesAsConfiguration\ControllerFactory'
        ),
    ),
    'view_helpers' => array(
        'abstract_factories' => array(
            'Reliv\FactoriesAsConfiguration\ViewHelperFactory'
        ),
    ),
    'controller_plugins' => array(
        'abstract_factories' => array(
            'Reliv\FactoriesAsConfiguration\ControllerPluginFactory'
        ),
    )
);
