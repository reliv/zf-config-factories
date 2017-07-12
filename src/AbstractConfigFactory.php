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
use Interop\Container\ContainerInterface;

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
     * @var array cached config array
     */
    protected $config = null;

    /**
     * @var Instantiator | null
     */
    protected $instantiator;

    /**
     * (For ZF3 Support)
     * @TODO put the code from canCreateServiceWithName in here to avoid the extra call in zf3
     *
     * @param ContainerInterface $container
     * @param $name
     * @return bool
     */
    public function canCreate(ContainerInterface $container, $name)
    {
        return $this->canCreateServiceWithName($container, $name, $name);
    }

    /**
     * (For ZF3 Support)
     * @TODO put the code from createServiceWithName in here to avoid the extra call in zf3
     *
     * @param ContainerInterface $container
     * @param $name
     * @param array $options
     * @return mixed
     */
    public function __invoke(ContainerInterface $container, $name, array $options = null)
    {
        return $this->createServiceWithName($container, $name, $name);
    }

    /**
     * Determine if we can create a service with name
     * (For ZF2 Support)
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

        return $this->getFactoryConfig($serviceMgr, $requestedName) !== null;
    }

    /**
     * Create service with name
     * (For ZF2 Support)
     *
     * @param ServiceLocatorInterface $serviceMgr
     * @param $name
     * @param $requestedName
     * @return Object
     * @throws \Exception
     */
    public function createServiceWithName(
        ServiceLocatorInterface $serviceMgr,
        $name,
        $requestedName
    ) {
        if (!$this->serviceMgrIsRoot) {
            $serviceMgr = $serviceMgr->getServiceLocator();
        }

        $config = $this->getFactoryConfig($serviceMgr, $requestedName);

        if (!is_array($config)) {
            throw new \Exception('Service not found: ' . $requestedName);
        }

        if (isset($config['factory'])) {
            //Symfony-style factories that are services themselves
            if (is_array($config['factory'])) {
                $factoryServiceName = $config['factory'][0];
                $factoryMethod = $config['factory'][1];
                $factory = $serviceMgr->get($factoryServiceName);

                return $factory->$factoryMethod();
            }

            //Zend Expressive style factories that are invokable classes
            if (class_exists($config['factory'])) {
                $factoryClass = $config['factory'];
                $factory = new $factoryClass();

                return $factory->__invoke($serviceMgr, $name, $requestedName);
            }
        }

        /**
         * The 'class' key is optional. If it is not in the config, we assume
         * the service's name is its class name
         */
        if (isset($config['class'])) {
            $className = $config['class'];
        } else {
            $className = $requestedName;
        }

        if (isset($config['arguments'])) {
            if (!$this->instantiator) {
                $this->instantiator = new Instantiator();
            }

            $service = $this->instantiator->instantiateWithArguments(
                $className,
                $this->processArgs($serviceMgr, $config['arguments'])
            );
        } else {
            $service = new $className();
        }

        if (isset($config['calls'])) {
            foreach ($config['calls'] as $arguments) {
                $methodName = $arguments[0];
                $arguments = $arguments[1];
                call_user_func_array(
                    [$service, $methodName],
                    $this->processArgs($serviceMgr, $arguments)
                );
            }
        }

        return $service;
    }

    /**
     * Converts an service names to to an array of their corresponding services
     *
     * @param ServiceLocatorInterface $serviceMgr
     * @param array $argumentServiceNames
     *
     * @return array
     */
    protected function processArgs(
        ServiceLocatorInterface $serviceMgr,
        $argumentServiceNames
    ) {
        $services = [];
        foreach ($argumentServiceNames as $serviceName) {
            if (!is_array($serviceName)) {
                $services[] = $serviceMgr->get($serviceName);
            } else {
                if (array_key_exists('literal', $serviceName)) {
                    $services[] = $serviceName['literal'];
                }
            }
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
        if ($this->config === null && $serviceMgr->has('config')) {
            $config = $serviceMgr->get('config');
            if (isset($config[$this->serviceMgrKey][$this->configKey])) {
                $this->config = $config[$this->serviceMgrKey][$this->configKey];
            }
        }

        if (isset($this->config[$serviceName])) {
            return $this->config[$serviceName];
        }

        return null;
    }
}
