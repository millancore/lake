<?php

use Lake\Console\Application;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application as ConsoleApplication;

class ApplicationTest extends TestCase
{
    public function testAppIsIntanciable()
    {
        $app = new Application(getcwd());

        $this->assertInstanceOf(ConsoleApplication::class, $app);
    }

    public function testNotFoundConfigLakeFile()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Unable to locate lake.yml file');

        $app = new Application('invalid/path');
    }
}