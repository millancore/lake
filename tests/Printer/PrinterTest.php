<?php

use Lake\Printer\Printer;
use Lake\Tests\Support\Fixture\LakeGeneratorFixture;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

class PrinterTest extends TestCase
{

    private $printer;

    public function setUp() : void
    {
        $this->printer = new Printer(new Filesystem);
    }

    public function testPrintClass()
    {
        $class = LakeGeneratorFixture::fixture();

        $printed = $class->generate();
        
        $this->assertIsString($printed);
        $this->assertEquals(file_get_contents(__DIR__.'/classContent.txt'), $printed);
    }

    public function testPrintFile()
    {
        $class = LakeGeneratorFixture::fixture();

        $expectedFile = __DIR__.'/expectedClassFile.txt';

        $printed = $class->generate();
        $this->printer->print($class, $expectedFile);

        $this->assertEquals(file_get_contents($expectedFile), $printed);
    }

    public function testPrintFileWithName()
    {
        $class = LakeGeneratorFixture::fixture();

        $expectedFile = __DIR__.'/expectedClassName.txt';

        $printed = $class->generate();
        $this->printer->print($class, $expectedFile);

        $this->assertEquals(file_get_contents($expectedFile), $printed);
    }
}