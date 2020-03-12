<?php

use Lake\Validation\NameValidation;
use PHPUnit\Framework\TestCase;

class NameValidationTest extends TestCase
{
    public function testValidName()
    {
        $this->assertTrue(NameValidation::validate('ValidName'));
    }
}

