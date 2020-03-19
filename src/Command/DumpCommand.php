<?php

namespace Lake\Command;

use Lake\Process\ProcessManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;

class DumpCommand extends Command
{
    protected static $defaultName = 'dump';
    private $processManager;

    public function __construct(ProcessManager $processManager)
    {   
        parent::__construct();
        $this->processManager = $processManager;
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
                # Restore default dump
                $this->processManager->dump();
                throw new ProcessFailedException($secondProcess);
            }
        }

        $output->writeln('Successful!');

        return 0;
    }
}