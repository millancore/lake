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
    

    /**
     * Print classFile to php file.
     *
     * @param FileGenerator $file
     * @param string $path
     * @param string $name
     * @param string $ext
     * @return void
     */
    public function printFile(FileGenerator $file, string $path, string $name = null, string $ext = 'php')
    {
        if(!is_null($name)) {
            $path = str_replace(basename($path), $name, $path);
        }

        $this->filesystem->dumpFile($path.'.'.$ext, $file->generate());
    }

    
}