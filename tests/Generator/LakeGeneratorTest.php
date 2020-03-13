<?php

use Lake\Generator\LakeGenerator;
use Laminas\Code\Generator\ClassGenerator;
use PHPUnit\Framework\TestCase;

class LakeGeneratorTest extends TestCase
{
    private $generator;

    public function setUp() : void
    {
        $this->generator = new LakeGenerator(
            'TestClass',
            'Lake\Tests\Namespace',
            'show',
            [
                ['Int', 'id'],
                ['Request', 'request']
            ],
            ['Lake\Uses\Request']
        );
    }

    public function testGetClass()
    {
        $class = $this->generator->getClass();

        $this->assertInstanceOf(ClassGenerator::class, $class);
    }

    public function testGetTest()
    {
        $class = $this->generator->getTest();

        $this->assertInstanceOf(ClassGenerator::class, $class);
    }
}