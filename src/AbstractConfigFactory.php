<?php
/**
 * Config Driven Abstract Factory
 *
 * PHP version 5
 *
 * @category  ZF2 Modules
 * @package   RmZfConfigFactories
 * @author    Rod Mcnew
 * @copyright 2014 Rod Mcnew
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace Reliv\ZfConfigFactories;

use Reliv\ZfConfigFactories\Helper\Instantiator;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Config Driven Abstract Factory
 *
 * PHP version 5
 *
 * @category  ZF2 Modules
 * @package   RmZfConfigFactories
 * @author    Rod Mcnew
 * @copyright 2014 Rod Mcnew
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
abstract class AbstractConfigFactory implements AbstractFactoryInterface
{

    /**
     * @var string the config key of the target service manager
     */
    protected $serviceMgrKey = 'DEFINE IN CHILD';

    /**
     * @var bool used know it we must look for the real service locator inside the given service locator
     */
    protected $serviceMgrIsRoot = false;

    /**
     * @var string the config key we will look for in the service manager config
     */
    protected $configKey = 'config_factories';

    /**
     * @var null | array map of canoncalized service name to factory configuration
     */
    protected $configFactories = null;

    /**
     * @var array map of characters to be replaced through strtr
     */
    protected $canonicalNamesReplacements = [
        '-' => '',
        '_' => '',
        ' ' => '',
        '\\' => '',
        '/' => ''
    ];

    /**
     * @var Instantiator | null
     */
    protected $instantiator;

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
        //Handle service names with @'s before them.
        if (substr($name, 0, 1) == '@') {
            $name = substr($name, 1);
        }

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
        //Handle service names with @'s before them.
        if (substr($name, 0, 1) == '@') {
            $name = substr($name, 1);
        }

        if (!$this->serviceMgrIsRoot) {
            $serviceMgr = $serviceMgr->getServiceLocator();
        }

        $config = $this->getFactoryConfig($serviceMgr, $name);

        if (isset($config['factory'])) {
            $factoryServiceName = $config['factory'][0];
            $factoryMethod = $config['factory'][1];
            $factory = $serviceMgr->get($factoryServiceName);

            return $factory->$factoryMethod();
        }

        /**
         * The 'class' key is optional. If it is not in the config, we assume
         * the service's name is its class name
         */
        if (isset($config['class'])) {
            $className = $config['class'];
        } else {
            $className = $config['name'];
        }

        if (isset($config['arguments']) && count($config['arguments']) > 0) {
            if (!$this->instantiator) {
                $this->instantiator = new Instantiator();
            }

            $service = $this->instantiator->instantiateWithArguments(
                $className,
                $this->fetchServices($serviceMgr, $config['arguments'])
            );
        } else {
            $service = new $className();
        }

        if (isset($config['calls'])) {
            foreach ($config['calls'] as $methodName => $arguments) {
                call_user_func_array(
                    [$service, $methodName],
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
     * @param Array $argumentServiceNames
     *
     * @return array
     */
    protected function fetchServices(
        ServiceLocatorInterface $serviceMgr,
        $argumentServiceNames
    ) {
        $services = [];
        foreach ($argumentServiceNames as $serviceNames) {
            $services[] = $serviceMgr->get($serviceNames);
        }

        return $services;
    }

    /**
     * Returns the factory configuration for a given service name
     *
     * @param ServiceLocatorInterface $serviceMgr
     * @param String $serviceName
     *
     * @return array | null
     */
    protected function getFactoryConfig(
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

        $this->configFactories = [];
        if (isset($config[$this->serviceMgrKey][$this->configKey])) {
            foreach ($config[$this->serviceMgrKey][$this->configKey] as $key => $value) {
                //Handle service names with @'s before them.
                if (substr($key, 0, 1) == '@') {
                    $key = substr($key, 1);
                }
                $this->configFactories[$this->canonicalizeName($key)]
                    = array_merge($value, ['name' => $key]);
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
        // this is just for performance instead of using str_replace
        return strtolower(
            strtr($name, $this->canonicalNamesReplacements)
        );
    }
}
