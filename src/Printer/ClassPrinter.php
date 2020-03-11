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
        $classFile = $this->fileGenerator->setClass($class);

        return $classFile->generate();                                                                                                                                                                                                                                              
    }

    public function printFile(ClassGenerator $class, string $path)
    {
        $content = $this->print($class, $path);
        $this->filesystem->dumpFile($path.'.php', $content);
    }
}