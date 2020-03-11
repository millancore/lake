<?php

namespace Lake\Command;

use Lake\Finder\VendorFinder;
use Lake\Process\ProcessManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Exception\ProcessFailedException;

class DumpCommand extends Command
{
    protected static $defaultName = 'dump';
    private $processManager;
    private $vendorFinder;

    protected function configure()
    {
        $this->processManager = new ProcessManager(new Filesystem);
        $this->vendorFinder = new VendorFinder;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Generating index vendor...');

        $process = $this->processManager->dumpOptimized();

        if ($process->isSuccessful()) {
            $secondProcess = $this->processManager->dumpVendor();

            if ($secondProcess->isSuccessful()) {
                $this->processManager->dumpAutoloads();
            } else {
                throw new ProcessFailedException($secondProcess);
            }
        }

        # Restore default dump
        $output->writeln('Successful!');

        return 0;
    }
}