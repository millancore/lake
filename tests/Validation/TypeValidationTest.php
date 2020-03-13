<?php

use Lake\Validation\TypeValidation;
use PHPUnit\Framework\TestCase;

class TypeValidationTest extends TestCase
{
    public function testValidateType()
    {
        $this->assertTrue(TypeValidation::validate('Command'));
    }

    /**
     * @dataProvider internalPhpTypesProvider
     */
    public function testValidInternalPhpType($type)
    {
        $this->assertTrue(TypeValidation::validate($type));
    }

     /**
     * @dataProvider internalPhpTypesProvider
     */
    public function testIsInternalPhpType($type)
    {
        $this->assertTrue(TypeValidation::isPhpType($type));
    }

    public function testVoidAsParameterException()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('void cannot be used as a parameter type');

        TypeValidation::validate('void');
    }

    public function testTrimClasType()
    {
        $this->assertTrue(TypeValidation::validate('\\Exception'));
    }

    public function testNullableType()
    {
        $this->assertTrue(TypeValidation::validate('?Exception'));
    }

    public function internalPhpTypesProvider()
    {
        return [
            ['int'],
            ['float'],
            ['string'],
            ['bool'],
            ['array'],
            ['callable'],
            ['iterable'],
            ['object']
        ];
    }

    
}