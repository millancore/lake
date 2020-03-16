<?php

namespace Lake\Validation;

class ClassValidation
{
    public static function validate(string $classPath)
    {
        $exists = false;

        if (!strpos($classPath, ':') === false) {
            $classPath = str_replace(':', DS, $classPath);            
        }

        if (file_exists($classPath.'.php')) {
            $exists = true;
        }

        return [$exists, $classPath];
    }
}