<?php

namespace Reliv\FactoriesAsConfiguration\Test;

use Reliv\FactoriesAsConfiguration\ConcreteFactory\ControllerFactory;

/**
 * Class ControllerFactoryTest
 * @package Reliv\FactoriesAsConfiguration\Test
 * @covers Reliv\FactoriesAsConfiguration\ConcreteFactory\ControllerFactory
 * @covers Reliv\FactoriesAsConfiguration\AbstractConfigFactory
 * @covers Reliv\FactoriesAsConfiguration\Helper\Instantiator
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
