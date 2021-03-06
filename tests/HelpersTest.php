<?php

use PHPUnit\Framework\TestCase;

class HelpersTest extends TestCase
{
    public function testBaseNameFromUnix()
    {
        $basename = base_name('Lake/App/Test/ClassName');

        $this->assertIsString($basename);
        $this->assertEquals('ClassName', $basename);
    }

    public function testBaseNameFromWindows()
    {
        $basename = base_name('Lake\\App\\Test\\ClassName');

        $this->assertIsString($basename);
        $this->assertEquals('ClassName', $basename);
    }

    public function testCamelCaseToSnake()
    {
        $snakeMethodName = snake('ThisIsACamelMethodName');

        $this->assertEquals('this_is_a_camel_method_name', $snakeMethodName);
    }
}