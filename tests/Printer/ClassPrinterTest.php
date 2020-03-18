<?php

use Lake\Printer\ClassPrinter;
use Lake\Tests\Support\Fixture\LakeGeneratorFixture;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

class ClassPrinterTest extends TestCase
{

    private $printer;

    public function setUp() : void
    {
        $this->printer = new ClassPrinter(new Filesystem);
    }

    public function testPrintClass()
    {
        $generator = LakeGeneratorFixture::fixture();

        $printed = $generator->getFile()->generate();

        file_put_contents('test.txt', $printed);
        
        $this->assertIsString($printed);
        $this->assertEquals(file_get_contents(__DIR__.'/classContent.txt'), $printed);
    }

    public function testPrintFile()
    {
        $generator = LakeGeneratorFixture::fixture();

        $expectedFile = __DIR__.'/expectedClassFile';

        $printed = $generator->getFile()->generate();
        $this->printer->printFile($generator->getFile(), $expectedFile, null, 'txt');

        $this->assertEquals(file_get_contents($expectedFile.'.txt'), $printed);
    }

    public function testPrintFileWithName()
    {
        $generator = LakeGeneratorFixture::fixture();

        $expectedFile = __DIR__.'/expectedClassName';

        $printed = $generator->getFile()->generate();
        $this->printer->printFile($generator->getFile(), $expectedFile, 'expectedClassName', 'txt');

        $this->assertEquals(file_get_contents($expectedFile.'.txt'), $printed);
    }
}