<?php

use Lake\Config;
use Lake\Entity\LakeClass;
use Lake\Generator\TestGenerator;
use Lake\Tests\Support\Fixture\LakeGeneratorFixture;
use PHPUnit\Framework\TestCase;

class TestGeneratorTest extends TestCase
{

    public function testGeneratorTestClass()
    {
        $generator = new TestGenerator(
            LakeGeneratorFixture::fixture(),
            new Config()
        );

        $this->assertInstanceOf(LakeClass::class, $generator->getTest());
    }
}