<?php

namespace Lake\Validation;

class ClassValidation
{
    public static function validate(string $classPath)
    {
        if (!strpos($classPath, ':') === false) {
            $classPath = str_replace(':', DS, $classPath);            
        }

        return $classPath;
    }
}