<?php
/**
 * Config Driven Abstract Factory
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

namespace Reliv\FactoriesAsConfiguration;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


/**
 * Config Driven Abstract Factory
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
class ServiceFactory implements AbstractFactoryInterface
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
        = [
            '-' => '',
            '_' => '',
            ' ' => '',
            '\\' => '',
            '/' => ''
        ];

    /**
     * Lookup for canonicalized names.
     *
     * @var array
     */
    protected $canonicalNames = [];

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
            $service = $this->instantiateWithArguments(
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
     * @param Array                   $argumentServiceNames
     *
     * @return array
     */
    public function fetchServices(
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
     * @param String                  $serviceName
     *
     * @return array | null
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
        $this->configFactories = [];
        if (isset($config[$this->serviceMgrKey][$this->configKey])) {
            foreach (
                $config[$this->serviceMgrKey][$this->configKey] as $key =>
                $value
            ) {
                $this->configFactories[$this->canonicalizeName($key)]
                    = array_merge($value, ['name' => $key]);
            }
        }
    }

    /**
     * Instantiate a class with the given arguments and return it.
     *
     * This function has 30 case statments to increase performance.
     * Instantiation using reflection takes 70% more time than standard
     * instantiation. We fall back to reflection only if a class has more
     * than 30 constructor arguments which should never happen.
     *
     * @param string $className the class name to instantiate
     * @param array  $arguments the arguments to pass in
     *
     * @return Object
     */
    public function instantiateWithArguments($className, Array $arguments)
    {
        $a = $arguments;
        switch (count($a)) {
            case 1:
                return new $className($a[0]);
                break;
            case 2:
                return new $className($a[0], $a[1]);
                break;
            case 3:
                return new $className($a[0], $a[1], $a[2]);
                break;
            case 4:
                return new $className($a[0], $a[1], $a[2], $a[3]);
                break;
            case 5:
                return new $className($a[0], $a[1], $a[2], $a[3], $a[4]);
                break;
            case 6:
                return new $className($a[0], $a[1], $a[2], $a[3], $a[4], $a[5]);
                break;
            case 7:
                return new $className($a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6]);
                break;
            case 8:
                return new $className($a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6], $a[7]);
                break;
            case 9:
                return new $className($a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6], $a[7], $a[8]);
                break;
            case 10:
                return new $className($a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6], $a[7], $a[8], $a[9]);
                break;
            case 11:
                return new $className($a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6], $a[7], $a[8], $a[9], $a[10]);
                break;
            case 12:
                return new $className($a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6], $a[7], $a[8], $a[9], $a[10], $a[11]);
                break;
            case 13:
                return new $className($a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6], $a[7], $a[8], $a[9], $a[10], $a[11], $a[12]);
                break;
            case 14:
                return new $className($a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6], $a[7], $a[8], $a[9], $a[10], $a[11], $a[12], $a[13]);
                break;
            case 15:
                return new $className($a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6], $a[7], $a[8], $a[9], $a[10], $a[11], $a[12], $a[13], $a[14]);
                break;
            case 16:
                return new $className($a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6], $a[7], $a[8], $a[9], $a[10], $a[11], $a[12], $a[13], $a[14], $a[15]);
                break;
            case 17:
                return new $className($a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6], $a[7], $a[8], $a[9], $a[10], $a[11], $a[12], $a[13], $a[14], $a[15], $a[16]);
                break;
            case 18:
                return new $className($a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6], $a[7], $a[8], $a[9], $a[10], $a[11], $a[12], $a[13], $a[14], $a[15], $a[16], $a[17]);
                break;
            case 19:
                return new $className($a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6], $a[7], $a[8], $a[9], $a[10], $a[11], $a[12], $a[13], $a[14], $a[15], $a[16], $a[17], $a[18]);
                break;
            case 20:
                return new $className($a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6], $a[7], $a[8], $a[9], $a[10], $a[11], $a[12], $a[13], $a[14], $a[15], $a[16], $a[17], $a[18], $a[19]);
                break;
            case 21:
                return new $className($a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6], $a[7], $a[8], $a[9], $a[10], $a[11], $a[12], $a[13], $a[14], $a[15], $a[16], $a[17], $a[18], $a[19], $a[20]);
                break;
            case 22:
                return new $className($a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6], $a[7], $a[8], $a[9], $a[10], $a[11], $a[12], $a[13], $a[14], $a[15], $a[16], $a[17], $a[18], $a[19], $a[20], $a[21]);
                break;
            case 23:
                return new $className($a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6], $a[7], $a[8], $a[9], $a[10], $a[11], $a[12], $a[13], $a[14], $a[15], $a[16], $a[17], $a[18], $a[19], $a[20], $a[21], $a[22]);
                break;
            case 24:
                return new $className($a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6], $a[7], $a[8], $a[9], $a[10], $a[11], $a[12], $a[13], $a[14], $a[15], $a[16], $a[17], $a[18], $a[19], $a[20], $a[21], $a[22], $a[23]);
                break;
            case 25:
                return new $className($a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6], $a[7], $a[8], $a[9], $a[10], $a[11], $a[12], $a[13], $a[14], $a[15], $a[16], $a[17], $a[18], $a[19], $a[20], $a[21], $a[22], $a[23], $a[24]);
                break;
            case 26:
                return new $className($a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6], $a[7], $a[8], $a[9], $a[10], $a[11], $a[12], $a[13], $a[14], $a[15], $a[16], $a[17], $a[18], $a[19], $a[20], $a[21], $a[22], $a[23], $a[24], $a[25]);
                break;
            case 27:
                return new $className($a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6], $a[7], $a[8], $a[9], $a[10], $a[11], $a[12], $a[13], $a[14], $a[15], $a[16], $a[17], $a[18], $a[19], $a[20], $a[21], $a[22], $a[23], $a[24], $a[25], $a[26]);
                break;
            case 28:
                return new $className($a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6], $a[7], $a[8], $a[9], $a[10], $a[11], $a[12], $a[13], $a[14], $a[15], $a[16], $a[17], $a[18], $a[19], $a[20], $a[21], $a[22], $a[23], $a[24], $a[25], $a[26], $a[27]);
                break;
            case 29:
                return new $className($a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6], $a[7], $a[8], $a[9], $a[10], $a[11], $a[12], $a[13], $a[14], $a[15], $a[16], $a[17], $a[18], $a[19], $a[20], $a[21], $a[22], $a[23], $a[24], $a[25], $a[26], $a[27], $a[28]);
                break;
            case 30:
                return new $className($a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6], $a[7], $a[8], $a[9], $a[10], $a[11], $a[12], $a[13], $a[14], $a[15], $a[16], $a[17], $a[18], $a[19], $a[20], $a[21], $a[22], $a[23], $a[24], $a[25], $a[26], $a[27], $a[28], $a[29]);
                break;
            default:
                $serviceClass = new \ReflectionClass($className);
                return $serviceClass->newInstanceArgs($a);
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
