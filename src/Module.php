<?php

/**
 * Module Config For ZF2
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @author    Rod McNew
 * @copyright 2012 Rod Mcnew
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 */

namespace Reliv\FactoriesAsConfiguration;

/**
 * ZF2 Module Config.  Required by ZF2
 *
 * ZF2 requires a Module.php file to load up all the Module Dependencies.  This
 * file has been included as part of the ZF2 standards.
 *
 * @category  Reliv
 * @author    Rod McNew
 * @copyright 2012 Rod Mcnew
 * @license   License.txt New BSD License
 * @version   Release: 1.0
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
                    'Reliv\FactoriesAsConfiguration\ConcreteFactory\ServiceFactory'
                ],
            ],
            'controllers' => [
                'abstract_factories' => [
                    'Reliv\FactoriesAsConfiguration\ConcreteFactory\ControllerFactory'
                ],
            ],
            'view_helpers' => [
                'abstract_factories' => [
                    'Reliv\FactoriesAsConfiguration\ConcreteFactory\ViewHelperFactory'
                ],
            ],
            'controller_plugins' => [
                'abstract_factories' => [
                    'Reliv\FactoriesAsConfiguration\ConcreteFactory\ControllerPluginFactory'
                ],
            ],
            'input_filters' => [
                'abstract_factories' => [
                    'Reliv\FactoriesAsConfiguration\ConcreteFactory\InputFilterFactory'
                ],
            ]
        ];
    }
}
