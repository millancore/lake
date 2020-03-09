<?php

namespace Lake\Finder;

use ReflectionClass;

class InternalFinder
{
    private $declaredClasses;

    public function __construct()
    {
        $this->declaredClasses = get_declared_classes();
    }

    public function findClass(string $findClass)
    {
        $result = array_filter(
            $this->declaredClasses,
            function($className) use ($findClass) {
                return call_user_func([
                    new ReflectionClass($className), 'isInternal'
                ]) && $className == $findClass;
            }
        );

        return current($result);
    }
}
