<?php

use Lake\Validation\ParameterValidation;
use PHPUnit\Framework\TestCase;

class ParameterValidationTest extends TestCase
{
    public function testValidateNameDefined()
    {
        $parts = ParameterValidation::validate('Command:test');

        $this->assertEquals(['Command','test'], $parts);
    }

    public function testValidateNameNotDefined()
    {
        $parts = ParameterValidation::validate('Command');

        $this->assertEquals(['Command','command'], $parts);
    }
}