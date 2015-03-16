<?php

class TestMock
{
    private $target;

    private function __construct($target)
    {
        $this->target = $target;
    }

    public static function on($target)
    {
        return new self($target);
    }

    public function __call($name, $args)
    {
        $method = new  ReflectionMethod($this->target, $name);
        $method->setAccessible(true);

        return $method->invokeArgs($this->target, $args);
    }

    public function __get($name)
    {
        $method = new  ReflectionProperty($this->target, $name);
        $method->setAccessible(true);

        return $method->getValue($this->target);
    }
}

