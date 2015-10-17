<?php

namespace Reliv\ZfConfigFactories\Test;

use Reliv\ZfConfigFactories\ConcreteFactory\ControllerFactory;

/**
 * Class ControllerFactoryTest
 * @package Reliv\ZfConfigFactories\Test
 * @covers Reliv\ZfConfigFactories\ConcreteFactory\ControllerFactory
 * @covers Reliv\ZfConfigFactories\AbstractConfigFactory
 * @covers Reliv\ZfConfigFactories\Helper\Instantiator
 */
class ControllerFactoryTest extends ServiceFactoryTest
{
    public function setup()
    {
        $this->serviceMgrConfigName = 'controllers';
        $this->serviceMgrIsRoot = false;
        $this->unit = new ControllerFactory();
    }

    /**
     * Build service locator mock
     *
     * @param $willReturnConfig
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    protected function buildServiceLocatorMock($willReturnConfig)
    {
        $realServiceLocator = parent::buildServiceLocatorMock($willReturnConfig);

        $serviceLocator = $this
            ->getMockBuilder('Zend\ServiceManager\ServiceLocatorInterface')
            ->setMethods(['getServiceLocator', 'get', 'has'])
            ->getMock();

        $serviceLocator->expects($this->any())
            ->method('getServiceLocator')
            ->will($this->returnValue($realServiceLocator));

        return $serviceLocator;
    }
}
