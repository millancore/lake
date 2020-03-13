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

    protected function configure()
    {
        $this->processManager = new ProcessManager(new Filesystem);
        $this->processManager->addComposerCommand('dump', ['dump-autoload']);
        $this->processManager->addComposerCommand('optimize',[
            'dump-autoload', '--optimize', '--no-dev'
        ]);
        $this->processManager->addPhpCommand('map', [
            LAKE_ROOT . '/src/Process/export', AUTOLOAD_PATH, LAKE_CACHE
        ]);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Generating index vendor...');

        $process = $this->processManager->optimize();

        if ($process->isSuccessful()) {
            $secondProcess = $this->processManager->map();

            if ($secondProcess->isSuccessful()) {
                $this->processManager->dump();
            } else {
                throw new ProcessFailedException($secondProcess);
            }
        }

        # Restore default dump
        $output->writeln('Successful!');

        return 0;
    }
}