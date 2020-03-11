<?php

namespace Lake\Validation;

class ParameterValidation
{
    public static function validate(string $argument) : array 
    {
        if (!strpos($argument, ':') === false) {
            list($type, $varName) = explode(':', $argument);
        } else {
            $type = $argument;
            $varName = lcfirst(TypeValidation::clear($argument));
        }

        TypeValidation::validate($type);
        NameValidation::validate($varName);

        return [$type, $varName];
    }
}