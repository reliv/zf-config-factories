<?php

namespace Reliv\ZfConfigFactories\Test;

class MockService
{
    public $constructorArgs;
    public $set1Args;
    public $set2Args;

    public function __construct()
    {
        $this->constructorArgs = func_get_args();
    }

    public function getConstructorArgs()
    {
        return $this->constructorArgs;
    }

    public function set1()
    {
        $this->set1Args = func_get_args();
    }

    public function set2()
    {
        $this->set2Args = func_get_args();
    }
}
