<?php
/**
 * ControllerConfigurator.php
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RmConfigurator
 * @author    Rod Mcnew
 * @copyright 2014 Rod Mcnew
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RmFactoriesAsConfiguration;

use Zend\ServiceManager\AbstractFactoryInterface;


/**
 * ControllerConfigurator
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RmConfigurator
 * @author    Rod Mcnew
 * @copyright 2014 Rod Mcnew
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class ControllerConfigDrivenFactory extends ConfigDrivenFactory implements AbstractFactoryInterface
{
    /**
     * @var string the config key of the target service manager
     */
    protected $serviceMgrKey = 'controllers';

    /**
     * @var bool used know it we must look for the real service locator inside the given service locator
     */
    protected $serviceMgrIsRoot = false;
} 