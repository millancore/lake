<?php

namespace Lake\Finder;

use Lake\Contract\FinderInterface;
use ReflectionClass;

class InternalFinder implements FinderInterface
{
    private $declaredClasses;

    public function __construct()
    {
        $this->declaredClasses = get_declared_classes();
    }

    public function findClass(string $className) : array
    {
        $result = array_filter(
            $this->declaredClasses,
            function($class) use ($className) {
                return call_user_func([
                    new ReflectionClass($class), 'isInternal'
                ]) && $class == $className;
            }
        );

        return $result;
    }
}
