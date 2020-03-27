<?php

namespace Lake\Printer;

use Lake\Entity\LakeClass;
use Symfony\Component\Filesystem\Filesystem;

class Printer
{
    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }
    
    public function print(LakeClass $class)
    {
        $this->filesystem->dumpFile($class->getFullPath(), $class->generate());
    }

    
}