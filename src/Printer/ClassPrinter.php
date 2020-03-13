<?php

namespace Lake\Printer;

use Laminas\Code\Generator\ClassGenerator;
use Laminas\Code\Generator\FileGenerator;
use Symfony\Component\Filesystem\Filesystem;

class ClassPrinter
{
    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }
    
    public function print(ClassGenerator $class)
    {
        $newFile = new FileGenerator;

        $classFile = $newFile->setClass($class);

        return $classFile->generate();                                                                                                                                                                                                                                              
    }

    public function printFile(ClassGenerator $class, string $path, string $name = null, string $ext = 'php')
    {
        $content = $this->print($class, $path);

        if(!is_null($name)) {
            $path = str_replace(basename($path), $name, $path);
        }

        $this->filesystem->dumpFile($path.'.'.$ext, $content);
    }

    
}