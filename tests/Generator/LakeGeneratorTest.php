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
            false,
            'app\Tests\Namespace',
            ['app' => 'App']
        );
    }

    public function testGetClass()
    {
        $class = $this->generator->getClass();

        $this->assertInstanceOf(ClassGenerator::class, $class);
    }

}