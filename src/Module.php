<?php

namespace Reliv\ZfConfigFactories;

/**
 * Module for ZF2 and ZF3
 */
class Module
{
    /**
     * getConfig() is a requirement for all Modules in ZF2.  This
     * function is included as part of that standard.  See Docs on ZF2 for more
     * information.
     *
     * @return array Returns array to be used by the ZF2 Module Manager
     */
    public function getConfig()
    {
        return [
            'service_manager' => [
                'abstract_factories' => [
                    'Reliv\ZfConfigFactories\ConcreteFactory\ServiceFactory'
                ],
            ],
            'controllers' => [
                'abstract_factories' => [
                    'Reliv\ZfConfigFactories\ConcreteFactory\ControllerFactory'
                ],
            ],
            'view_helpers' => [
                'abstract_factories' => [
                    'Reliv\ZfConfigFactories\ConcreteFactory\ViewHelperFactory'
                ],
            ],
            'controller_plugins' => [
                'abstract_factories' => [
                    'Reliv\ZfConfigFactories\ConcreteFactory\ControllerPluginFactory'
                ],
            ],
            'input_filters' => [
                'abstract_factories' => [
                    'Reliv\ZfConfigFactories\ConcreteFactory\InputFilterFactory'
                ],
            ]
        ];
    }
}
