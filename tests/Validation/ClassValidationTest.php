<?php

use Lake\Validation\ClassValidation;
use PHPUnit\Framework\TestCase;

class ClassValidationTest extends TestCase
{
    public function testValidateSimpleNotationPath()
    {
        $class = ClassValidation::validate('src\App\Http\Request');

        $this->assertIsString($class);
        $this->assertEquals('src\App\Http\Request', $class);
    }

    public function testValidateUseColonNotationPath()
    {
        $class = ClassValidation::validate('src:App:Http:Request');
        
        $this->assertIsString($class);
        $this->assertEquals('src'.DS.'App'.DS.'Http'.DS.'Request', $class);
    }

}