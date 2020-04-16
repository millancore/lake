<?php

namespace Lake\Printer;

use Lake\Contract\GeneratorInterface;
use Symfony\Component\Filesystem\Filesystem;

class Printer
{
    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }
    
    public function print(GeneratorInterface $class, $path, $name = null, $ext = null )
    {
        $this->filesystem->dumpFile($path, $class->generate());
    }
    
}
