<?php

use Lake\Validation\NameValidation;
use PHPUnit\Framework\TestCase;

class NameValidationTest extends TestCase
{
    public function testValidName()
    {
        $this->assertTrue(NameValidation::validate('ValidName'));
    }

    public function testInvalidName()
    {
        $name = '02ClasName';

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf('Provided name "%s" is invalid name: must conform "%s"',
            $name, NameValidation::$validIdentifierMatcher)
        );

        NameValidation::validate($name);

    }
}

