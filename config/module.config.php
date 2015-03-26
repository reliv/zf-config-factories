<?php
return array(
    'service_manager' => array(
        'abstract_factories' => array(
            'RmFactoriesAsConfiguration\ConfigDrivenAbstractFactory'
        ),
    ),
    'controllers' => array(
        'abstract_factories' => array(
            'RmFactoriesAsConfiguration\ControllerConfigDrivenAbstractFactory'
        ),
    )
);
