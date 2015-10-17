<?php

namespace Reliv\ZfConfigFactories\Test;

use Reliv\ZfConfigFactories\ConcreteFactory\ServiceFactory;

/**
 * Class ServiceFactoryTest
 * @package Reliv\ZfConfigFactories\Test
 * @covers Reliv\ZfConfigFactories\ConcreteFactory\ServiceFactory
 * @covers Reliv\ZfConfigFactories\AbstractConfigFactory
 * @covers Reliv\ZfConfigFactories\Helper\Instantiator
 */
class ServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $unit;

    protected $serviceMgrConfigName = 'service_manager';

    /**
     * @var bool used know it we must look for the real service locator inside the given service locator
     */
    protected $serviceMgrIsRoot = true;

    public function setup()
    {
        $this->unit = new ServiceFactory();
    }

    public function testCanCreateServiceWithNameWhenTrue()
    {
        $serviceLocator = $this->buildServiceLocatorMock(
            [
                $this->serviceMgrConfigName => [
                    'config_factories' => [
                        'App\Email\EmailService' => [
                            'arguments' => [
                                'Name\Of\A\Service\I\Want\To\Inject',
                                'Name\Of\A\AnotherService\I\Want\To\Inject'
                            ],
                        ]
                    ]
                ]
            ]
        );

        $this->assertTrue(
            $this->unit->canCreateServiceWithName($serviceLocator, 'appemailemailservice', 'App\Email\EmailService')
        );
    }

    public function testCanCreateServiceWithNameWhenFalse()
    {
        $serviceLocator = $this->buildServiceLocatorMock(
            [
                $this->serviceMgrConfigName => [
                    'config_factories' => [
                        'App\Email\EmailService' => [
                            'arguments' => [
                                'Name\Of\A\Service\I\Want\To\Inject',
                                'Name\Of\A\AnotherService\I\Want\To\Inject'
                            ],
                        ]
                    ]
                ]
            ]
        );

        $this->assertFalse(
            $this->unit->canCreateServiceWithName($serviceLocator, 'appemailotherservice', 'App\Email\OtherService')
        );
    }

    public function testCreateServiceWithNameWithNoConstructorArgsAndNoClassName()
    {
        $serviceLocator = $this->buildServiceLocatorMock(
            [
                $this->serviceMgrConfigName => [
                    'config_factories' => [
                        'Reliv\ZfConfigFactories\Test\MockService' => []
                    ]
                ]
            ]
        );

        $this->assertTrue(
            $this->unit->createServiceWithName(
                $serviceLocator,
                'relivfactoriesasconfigurationtestmockservice',
                'Reliv\ZfConfigFactories\Test\MockService'
            ) instanceof MockService
        );
    }

    public function testCreateServiceWithNameWithSetterCalls()
    {
        if (!$this->serviceMgrIsRoot) {
            return;
        }
        $serviceLocator = $this->buildServiceLocatorMock(
            [
                $this->serviceMgrConfigName => [
                    'config_factories' => [
                        'Reliv\ZfConfigFactories\Test\MockService' => [
                            'calls' => [
                                'set1' => ['hi', 'hiagain'],
                                'set2' => ['aloha']
                            ]
                        ]
                    ]
                ]
            ]
        );

        $serviceLocator->expects($this->at(1))
            ->method('get')
            ->will($this->returnValue('hiservice'));
        $serviceLocator->expects($this->at(2))
            ->method('get')
            ->will($this->returnValue('hiagainservice'));
        $serviceLocator->expects($this->at(3))
            ->method('get')
            ->will($this->returnValue('alohaservice'));

        $service = $this->unit->createServiceWithName(
            $serviceLocator,
            'relivfactoriesasconfigurationtestmockservice',
            'Reliv\ZfConfigFactories\Test\MockService'
        );
        $this->assertTrue(
            $service instanceof MockService
        );
        $this->assertEquals(['hiservice', 'hiagainservice'], $service->set1Args);
        $this->assertEquals(['alohaservice'], $service->set2Args);
    }

    public function testCreateServiceWithNameWithNoConstructorArgsAndClassNameDifferent()
    {
        $serviceLocator = $this->buildServiceLocatorMock(
            [
                $this->serviceMgrConfigName => [
                    'config_factories' => [
                        'MockService' => [
                            'class' => 'Reliv\ZfConfigFactories\Test\MockService'
                        ]
                    ]
                ]
            ]
        );

        $this->assertTrue(
            $this->unit->createServiceWithName(
                $serviceLocator,
                'mockservice',
                'MockService'
            ) instanceof MockService
        );
    }

    public function testCreateServiceWithNameWithOneToFortyConstructorArgs()
    {
        if (!$this->serviceMgrIsRoot) {
            return;
        }
        $args = [];
        $services = [];
        for ($i = 1; $i < 41; $i++) {
            $args[] = 'arg' . $i;
            $services[] = 'service' . $i;
            $this->setup();
            $serviceLocator = $this->buildServiceLocatorMock(
                [
                    $this->serviceMgrConfigName => [
                        'config_factories' => [
                            'Reliv\ZfConfigFactories\Test\MockService' => [
                                'arguments' => $args
                            ]
                        ]
                    ]
                ]
            );

            if (!$this->serviceMgrIsRoot) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            for ($ii = 1; $ii <= $i; $ii++) {
                $serviceLocator->expects($this->at($ii))
                    ->method('get')
                    ->will($this->returnValue('service' . $ii));
            }

            $service = $this->unit->createServiceWithName(
                $serviceLocator,
                'relivfactoriesasconfigurationtestmockservice',
                'Reliv\ZfConfigFactories\Test\MockService'
            );
            $this->assertTrue($service instanceof MockService);
            $this->assertEquals($services, $service->getConstructorArgs());
        }
    }

    /**
     * Build service locator mock
     *
     * @param $willReturnConfig
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    protected function buildServiceLocatorMock($willReturnConfig)
    {
        $serviceLocator = $this
            ->getMockBuilder('Zend\ServiceManager\ServiceLocatorInterface')
            ->setMethods(['getServiceLocator', 'get', 'has'])
            ->getMock();

        $serviceLocator->expects($this->at(0))
            ->method('get')
            ->will($this->returnValue($willReturnConfig));

        return $serviceLocator;
    }
}
