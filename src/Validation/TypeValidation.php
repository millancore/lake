<?php

namespace Lake\Validation;

use InvalidArgumentException;

class TypeValidation
{
    /**
     * @var bool
     */
    private $isInternalPhpType;

    /**
     * @var string
     */
    private $type;

    /**
     * @var bool
     */
    private $nullable;

    /**
     * @var string[]
     *
     * @link http://php.net/manual/en/functions.arguments.php#functions.arguments.type-declaration
     */
    private static $internalPhpTypes = [
        'void',
        'int',
        'float',
        'string',
        'bool',
        'array',
        'callable',
        'iterable',
        'object'
    ];

    public static function validate(string $type)
    {
        $trimmedType = self::clear($type);

        NameValidation::validate($trimmedType);

        $isInternalPhpType = self::isInternalPhpType($trimmedType);

        if ($isInternalPhpType && 'void' === strtolower($trimmedType)) {
            throw new InvalidArgumentException(sprintf('%s cannot be used as a parameter type', $trimmedType));
        }

    }

    public static function isPhpType(string $type)
    {
        return self::isInternalPhpType(self::clear($type));
    }

     public static function clear($type)
     {
        return self::trimType(self::trimNullable($type));      
     } 

    /**
     * @param string $type
     *
     * @return string[] the trimmed string
     */
    private static function trimNullable($type)
    {
        if (0 === strpos($type, '?')) {
            return substr($type, 1);
        }

        return $type;
    }

        /**
     * @param string $type
     *
     * @return string[]  the trimmed string
     */
    private static function trimType($type)
    {
        if (0 === strpos($type, '\\')) {
            return substr($type, 1);
        }

        return $type;
    }

    
    /**
     * @param string $type
     *
     * @return bool
     */
    private static function isInternalPhpType($type)
    {
        return in_array(strtolower($type), self::$internalPhpTypes, true);
    }
}