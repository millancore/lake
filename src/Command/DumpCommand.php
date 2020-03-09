<?php

namespace Lake\Command;

use Lake\Composer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class DumpCommand extends Command
{
    protected static $defaultName = 'dump';
    private $composer;

    protected function configure()
    {
        $this->composer = new Composer(new Filesystem);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->composer->dumpOptimized();
        
        $output->writeln('Generating optimized autoload files');

        return 0;
    }
}