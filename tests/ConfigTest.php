<?php

use Lake\Config;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

class ConfigTest extends TestCase
{

    public function testLoadComposerFile()
    {
        $composer = (new Config())->loadComposerFile(getcwd());

        $psr4Autoload = $composer['autoload']['psr-4'];
        
        $this->assertEquals([
            'Lake\\' => 'src/',
            'App\\' => 'app/',
        ], $psr4Autoload);
    }

    public function testLoadComposerFileInvalidPath()
    {
        $invalidPath = 'invalid/path/to/composer.json';

        $this->expectException(FileNotFoundException::class);
        $this->expectExceptionMessage(sprintf('composer.json not found in %s', $invalidPath));

        (new Config())->loadComposerFile($invalidPath);
    }

    public function testGetOptionsByDefault()
    {
        $configDefaultOptions = (new Config())->getOptions();
        
        $expectedOptions = [
            'test' => [
                'dir' => 'tests/',
                'extends' => 'PHPUnit\\Framework\\TestCase',
                'style' => 'camel',
            ],
            'exepath' => getcwd(),
            'mode' => 'loose',
            'src' => [
                'app/' => 'App\\',
                'src/' => 'Lake\\'
            ],
        ];

        $this->assertEquals($expectedOptions, $configDefaultOptions);
    }

}

