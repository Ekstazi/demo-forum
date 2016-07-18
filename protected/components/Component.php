<?php

namespace app\components;


class Component
{
    function __get($name)
    {
        $methodName = 'get' . ucfirst($name);
        if (!method_exists(get_class($this), $methodName)) {
            throw new \Exception('Unknown property: ' . $name);
        }

        return call_user_func([$this, $methodName]);
    }

    function __set($name, $value)
    {
        $methodName = 'set' . ucfirst($name);
        if (!method_exists(get_class($this), $methodName)) {
            throw new \Exception('Unknown property: ' . $name);
        }

        call_user_func([$this, $methodName], $value);
    }

    public function canSetProperty($propertyName)
    {
        $className = get_class($this);
        return property_exists($className, $propertyName)
            || method_exists($className, 'set' . ucfirst($propertyName));
    }

    public function canGetProperty($propertyName)
    {
        $className = get_class($this);
        return property_exists($className, $propertyName)
        || method_exists($className, 'get' . ucfirst($propertyName));
    }
}