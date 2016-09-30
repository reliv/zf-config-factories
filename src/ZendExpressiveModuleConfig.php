<?php

namespace Reliv\ZfConfigFactories;

/**
 * Module for Zend Expressive per:
 * https://zend-expressive.readthedocs.io/en/latest/cookbook/modular-layout/
 *
 * Class ZendExpressiveModuleConfig
 * @package Reliv\ZfConfigFactories
 */
class ZendExpressiveModuleConfig
{
    public function __invoke()
    {
        return [
            'dependencies' => [
                'abstract_factories' => [
                    ConcreteFactory\DependenciesFactory::class
                ],
            ],
        ];
    }
}
