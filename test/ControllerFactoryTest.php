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
}
