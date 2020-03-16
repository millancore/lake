<?php

use Lake\Validation\ClassValidation;
use PHPUnit\Framework\TestCase;

class ClassValidationTest extends TestCase
{
    public function testValidateSimpleNotationPath()
    {
        list($exist, $class) = ClassValidation::validate('app\Http\Request');

        $this->assertIsString($class);
        $this->assertFalse($exist);
        $this->assertEquals('app\Http\Request', $class);
    }

    public function testValidateUseColonNotationPath()
    {
        list($exist, $class) = ClassValidation::validate('app:Http:Request');
        
        $this->assertIsString($class);
        $this->assertFalse($exist);
        $this->assertEquals('app'.DS.'Http'.DS.'Request', $class);
    }

}