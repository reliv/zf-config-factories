<?php
/**
 * ConfigDrivenFactory.php
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   PwsContactOwner\src
 * @author    Rod Mcnew
 * @copyright 2014 Rod Mcnew
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RmFactoriesAsConfiguration;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


/**
 * ConfigDrivenFactory
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   PwsContactOwner\src
 * @author    Rod Mcnew
 * @copyright 2014 Rod Mcnew
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class ControllerConfigDrivenAbstractFactory implements AbstractFactoryInterface
{

    /**
     * @var string the config key we will look for in the service manager config
     */
    protected $configKey = 'config_factories';

    /**
     * @var string the config key of the target service manager
     */
    protected $serviceMgrKey = 'service_manager';

    /**
     * @var bool used know it we must look for the real service locator inside the given service locator
     */
    protected $serviceMgrIsRoot = true;

    /**
     * @var null | array map of canoncalized service name to factory configuration
     */
    protected $configFactories = null;

    /**
     * @var array map of characters to be replaced through strtr
     */
    protected $canonicalNamesReplacements
        = array(
            '-' => '',
            '_' => '',
            ' ' => '',
            '\\' => '',
            '/' => ''
        );

    /**
     * Lookup for canonicalized names.
     *
     * @var array
     */
    protected $canonicalNames = array();

    /**
     * Determine if we can create a service with name
     *
     * @param ServiceLocatorInterface $serviceMgr
     * @param                         $name
     * @param                         $requestedName
     *
     * @return bool
     */
    public function canCreateServiceWithName(
        ServiceLocatorInterface $serviceMgr,
        $name,
        $requestedName
    ) {
        if (!$this->serviceMgrIsRoot) {
            $serviceMgr = $serviceMgr->getServiceLocator();
        }

        return $this->getFactoryConfig($serviceMgr, $name) !== null;
    }

    /**
     * Create service with name
     *
     * @param ServiceLocatorInterface $serviceMgr
     * @param                         $name
     * @param                         $requestedName
     *
     * @return mixed
     */
    public function createServiceWithName(
        ServiceLocatorInterface $serviceMgr,
        $name,
        $requestedName
    ) {
        if (!$this->serviceMgrIsRoot) {
            $serviceMgr = $serviceMgr->getServiceLocator();
        }

        $config = $this->getFactoryConfig($serviceMgr, $name);

        if (isset($config['arguments'])) {
            $serviceClass = new \ReflectionClass($config['class']);
            $service = $serviceClass->newInstanceArgs(
                $this->fetchServices($serviceMgr, $config['arguments'])
            );
        } else {
            $service = new $config['class']();
        }

        if (isset($config['calls'])) {
            foreach ($config['calls'] as $methodName => $arguments) {
                call_user_func_array(
                    array($service, $methodName),
                    $this->fetchServices($serviceMgr, $arguments)
                );
            }
        }

        return $service;
    }

    /**
     * Converts an service names to to an array of their corresponding services
     *
     * @param ServiceLocatorInterface $serviceMgr
     * @param Array                   $argumentServiceNames
     *
     * @return array
     */
    public function fetchServices(
        ServiceLocatorInterface $serviceMgr,
        $argumentServiceNames
    ) {
        $services = array();
        foreach ($argumentServiceNames as $serviceNames) {
            $services[] = $serviceMgr->get($serviceNames);
        }
        return $services;
    }

    /**
     * Returns the factory configuration for a given service name
     *
     * @param ServiceLocatorInterface $serviceMgr
     * @param String                  $serviceName
     *
     * @return null
     */
    public function getFactoryConfig(
        ServiceLocatorInterface $serviceMgr,
        $serviceName
    ) {
        if (!is_array($this->configFactories)) {
            $this->buildFactoryConfig($serviceMgr);
        }
        return isset($this->configFactories[$serviceName])
            ? $this->configFactories[$serviceName] : null;
    }

    /**
     * Builds our array of factory configs keyed by canonicalized service names
     *
     * @param ServiceLocatorInterface $serviceMgr
     */
    public function buildFactoryConfig(ServiceLocatorInterface $serviceMgr)
    {
        $config = $serviceMgr->get('config');
        $this->configFactories = array();
        if (isset($config[$this->serviceMgrKey][$this->configKey])) {
            foreach (
                $config[$this->serviceMgrKey][$this->configKey] as $key =>
                $value
            ) {
                $this->configFactories[$this->canonicalizeName($key)]
                    = $value;
            }
        }
    }

    /**
     * Canonicalize name
     *
     * @param  string $name
     *
     * @return string
     */
    protected function canonicalizeName($name)
    {
        if (isset($this->canonicalNames[$name])) {
            return $this->canonicalNames[$name];
        }

        // this is just for performance instead of using str_replace
        return $this->canonicalNames[$name] = strtolower(
            strtr($name, $this->canonicalNamesReplacements)
        );
    }
}