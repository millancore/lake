<?php

namespace Lake\Printer;

use Laminas\Code\Generator\ClassGenerator;
use Laminas\Code\Generator\FileGenerator;
use Symfony\Component\Filesystem\Filesystem;

class ClassPrinter
{
    private $fileGenerator;
    private $filesystem;

    public function __construct(FileGenerator $fileGenerator, Filesystem $filesystem)
    {
        $this->fileGenerator = $fileGenerator;
        $this->filesystem = $filesystem;
    }
    
    public function print(ClassGenerator $class, string $path)
    {
        $newFile = new FileGenerator;

        $classFile = $newFile->setClass($class);

        return $classFile->generate();                                                                                                                                                                                                                                              
    }

    public function printFile(ClassGenerator $class, string $path, string $name = null)
    {
        $content = $this->print($class, $path);

        if(!is_null($name)) {
            $path = str_replace(basename($path), $name, $path);
        }

        $this->filesystem->dumpFile($path.'.php', $content);
    }

    
}