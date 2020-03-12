<?php

use Lake\Validation\ClassValidation;
use PHPUnit\Framework\TestCase;

class ClassValidationTest extends TestCase
{
    public function testValidationClass()
    {
        $class = ClassValidation::validate('Request');

        $this->assertIsString($class);
    }

}