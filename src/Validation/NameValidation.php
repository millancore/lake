<?php

namespace Lake\Validation;

use InvalidArgumentException;

class NameValidation 
{

    /**
     * @var string a regex pattern to match valid class names or types
     */
    public static $validIdentifierMatcher = '/^[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*'
        . '(\\\\[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*)*$/';

    /**
     * Validate name class, function or type
     *
     * @param string $name
     * @return boolean
     * @throws InvalidArgumentException
     */
    public static function validate(string $name) : bool
    {
        if (! preg_match(self::$validIdentifierMatcher, $name)) {
            throw new InvalidArgumentException(sprintf(
                'Provided name "%s" is invalid name: must conform "%s"',
                $name,
                self::$validIdentifierMatcher
            ));
        }

        return true;
    }
}
