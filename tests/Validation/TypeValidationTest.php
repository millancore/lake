<?php

use Lake\Validation\TypeValidation;
use PHPUnit\Framework\TestCase;

class TypeValidationTest extends TestCase
{
    public function testValidateType()
    {
        $this->assertFalse(TypeValidation::validate('Command'));
    }

    
}