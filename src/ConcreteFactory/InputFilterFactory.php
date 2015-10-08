<?php
/**
 * Input Filter Config Driven Abstract Factory
 *
 * PHP version 5
 *
 * @category  ZF2 Modules
 * @package   RmFactoriesAsConfiguration
 * @author    Rod Mcnew
 * @copyright 2014 Rod Mcnew
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace Reliv\FactoriesAsConfiguration\ConcreteFactory;

use Reliv\FactoriesAsConfiguration\AbstractConfigFactory;
use Zend\ServiceManager\AbstractFactoryInterface;

/**
 * Input Filter Config Driven Abstract Factory
 *
 * PHP version 5
 *
 * @category  ZF2 Modules
 * @package   RmFactoriesAsConfiguration
 * @author    Rod Mcnew
 * @copyright 2014 Rod Mcnew
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class InputFilterFactory extends AbstractConfigFactory implements AbstractFactoryInterface
{
    /**
     * @var string the config key of the target service manager
     */
    protected $serviceMgrKey = 'input_filters';
}
