<?php

namespace Lake\Printer;

use Laminas\Code\Generator\FileGenerator;
use Symfony\Component\Filesystem\Filesystem;

class Printer
{
    private $fileGenerator;
    private $filesystem;

    public function __construct(FileGenerator $fileGenerator, Filesystem $filesystem)
    {
        $this->fileGenerator = $fileGenerator;
        $this->filesystem = $filesystem;
    }
}
