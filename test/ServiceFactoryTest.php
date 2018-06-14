<?php

namespace Reliv\ZfConfigFactories\Test;

use PHPUnit\Framework\TestCase;
use Reliv\ZfConfigFactories\ConcreteFactory\ServiceFactory;

/**
 * Class ServiceFactoryTest
 * @package Reliv\ZfConfigFactories\Test
 * @covers Reliv\ZfConfigFactories\ConcreteFactory\ServiceFactory
 * @covers Reliv\ZfConfigFactories\AbstractConfigFactory
 * @covers Reliv\ZfConfigFactories\Helper\Instantiator
 */
class ServiceFactoryTest extends TestCase
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
            $this->unit->canCreateServiceWithName($serviceLocator, 'App\Email\EmailService', 'App\Email\EmailService')
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
            $this->unit->canCreateServiceWithName(
                $serviceLocator,
                'NotApp\Email\EmailService',
                'App\Email\OtherService'
            )
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
                'Reliv\ZfConfigFactories\Test\MockService',
                'Reliv\ZfConfigFactories\Test\MockService'
            ) instanceof MockService
        );
    }

    public function testCreateServiceWithNameWithSetterCalls()
    {
        $serviceLocator = $this->buildServiceLocatorMock(
            [
                $this->serviceMgrConfigName => [
                    'config_factories' => [
                        'Reliv\ZfConfigFactories\Test\MockService' => [
                            'calls' => [
                                ['set1', ['hi', 'hiagain']],
                                ['set2', ['aloha']]
                            ]
                        ]
                    ]
                ]
            ]
        );

        $serviceLocator->allows()->get('hi')->andReturns('hiservice');
        $serviceLocator->allows()->get('hiagain')->andReturns('hiagainservice');
        $serviceLocator->allows()->get('aloha')->andReturns('alohaservice');

        $service = $this->unit->createServiceWithName(
            $serviceLocator,
            'Reliv\ZfConfigFactories\Test\MockService',
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
                'MockService',
                'MockService'
            ) instanceof MockService
        );
    }

    public function testCreateServiceWithNameWithOneToFortyConstructorArgs()
    {
        $args = [];
        $services = [];
        for ($i = 1; $i < 41; $i++) {
            $args[] = 'arg' . $i;
            $services [] = 'service' . $i;
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
                $serviceLocator->allows()->get('arg' . $ii)->andReturns('service' . $ii);
            }

            $service = $this->unit->createServiceWithName(
                $serviceLocator,
                'Reliv\ZfConfigFactories\Test\MockService',
                'Reliv\ZfConfigFactories\Test\MockService'
            );
            $this->assertTrue($service instanceof MockService);
            $this->assertEquals($services, $service->getConstructorArgs());
        }
    }

    public function testCreateServiceLiteralArgsAndCalls()
    {
        $serviceLocator = $this->buildServiceLocatorMock(
            [
                $this->serviceMgrConfigName => [
                    'config_factories' => [
                        'Reliv\ZfConfigFactories\Test\MockService' => [
                            'arguments' => [
                                ['literal' => 'arg1'],
                                ['literal' => 'arg2'],
                            ],
                            'calls' => [
                                ['set1', [['literal' => 'call1arg1'], ['literal' => 'call1arg2']]],
                                ['set2', [['literal' => 'call2arg1']]]
                            ]
                        ]
                    ]
                ]
            ]
        );

        $service = $this->unit->createServiceWithName(
            $serviceLocator,
            'Reliv\ZfConfigFactories\Test\MockService',
            'Reliv\ZfConfigFactories\Test\MockService'
        );
        $this->assertTrue(
            $service instanceof MockService
        );
        $this->assertEquals(['arg1', 'arg2'], $service->getConstructorArgs());
        $this->assertEquals(['call1arg1', 'call1arg2'], $service->set1Args);
        $this->assertEquals(['call2arg1'], $service->set2Args);
    }

    public function testCreateServiceUsingFromConfigInArgsAndCalls()
    {
        $serviceLocator = $this->buildServiceLocatorMock(
            [
                $this->serviceMgrConfigName => [
                    'config_factories' => [
                        'Reliv\ZfConfigFactories\Test\MockService' => [
                            'arguments' => [
                                ['from_config' => 'flatThing'],
                                [
                                    'from_config' => ['funModule', 'funSection', 'deepThing'],
                                ]
                            ],
                            'calls' => [
                                [
                                    'set1',
                                    [
                                        ['from_config' => 'flatThing'],
                                        ['from_config' => ['funModule', 'funSection', 'deepThing']]
                                    ]
                                ],
                                [
                                    'set2',
                                    [
                                        ['from_config' => ['funModule', 'funSection', 'deepThing']]
                                    ]
                                ]
                            ]
                        ]

                    ]
                ],
                'funModule' => [
                    'funSection' => [
                        'deepThing' => 'deepValue'
                    ]
                ],
                'flatThing' => 'flatValue'
            ]
        );


        $service = $this->unit->createServiceWithName(
            $serviceLocator,
            'Reliv\ZfConfigFactories\Test\MockService',
            'Reliv\ZfConfigFactories\Test\MockService'
        );
        $this->assertTrue(
            $service instanceof MockService
        );
        $this->assertEquals(['flatValue', 'deepValue'], $service->getConstructorArgs());
        $this->assertEquals(['flatValue', 'deepValue'], $service->set1Args);
        $this->assertEquals(['deepValue'], $service->set2Args);
    }

    /**
     * Build service locator mock
     *
     * @param $willReturnConfig
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    protected function buildServiceLocatorMock($willReturnConfig)
    {
        $serviceLocator = \Mockery::mock('Zend\ServiceManager\ServiceLocatorInterface');
        $serviceLocator->allows()->has(\Mockery::any())->andReturns(true);
        $serviceLocator->allows()->get('config')->andReturns($willReturnConfig);
        $serviceLocator->allows()->getServiceLocator()->andReturns($serviceLocator);

        return $serviceLocator;
    }
}
