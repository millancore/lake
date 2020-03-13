<?php

namespace Lake\Process;

use InvalidArgumentException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;

class ProcessManager
{
    /**
     * The filesystem instance.
     *
     * @var Symfony\Component\Filesystem\Filesystem
     */
    protected $files;

    /**
     * The working path to regenerate from.
     *
     * @var string|null
     */
    protected $workingPath;


    /**
     * Storage commands
     *
     * @var array
     */
    protected $commands;

    /**
     * Create a new Composer manager instance.
     *
     * @param  Symfony\Component\Filesystem\Filesystem $files
     * @param  string|null  $workingPath
     * @return void
     */
    public function __construct(Filesystem $files, $workingPath = null)
    {
        $this->files = $files;
        $this->workingPath = $workingPath;
    }

    /**
     * Register new command
     *
     * @param string $name
     * @param array $command
     * @return void
     */
    public function register(string $name, array $command)
    {
        $this->commands[$name] = $command;
    }

    /**
     * Add php command
     *
     * @param string $name
     * @param array $command
     * @return void
     */
    public function addPhpCommand(string $name, array $command)
    {
        $command = array_merge([$this->phpBinary()], $command);
        $this->register($name, $command);
    }

    /**
     * Add Composer command
     *
     * @param string $name
     * @param array $command
     * @return void
     */
    public function addComposerCommand(string $name, array $command)
    {
        $command = array_merge($this->findComposer(), $command);
        $this->register($name, $command);
    }

    /**
     * Get registered command
     *
     * @param string $name
     * @return array 
     */
    public function getCommand(string $name): array
    {
        if (!isset($this->commands[$name])) {
            throw new InvalidArgumentException(sprintf('The command "%s" has not been registered', $name));
        }

        return $this->commands[$name];
    }


    /**
     * Runn registered command
     *
     * @param string $name
     * @param array $arguments
     * @return \Symfony\Component\Process\Process
     */
    public function __call(string $name, array $arguments = [])
    {
        return $this->run($this->getCommand($name));
    }

    /**
     * Run command
     *
     * @param array $command
     * @return \Symfony\Component\Process\Process
     */
    private function run(array $command)
    {
        $process = $this->getProcess($command);
        $process->setTimeout(null);
        $process->run();

        return $process;
    }


    /**
     * Get the composer command for the environment.
     *
     * @return array
     */
    protected function findComposer()
    {
        if ($this->files->exists($this->workingPath . '/composer.phar')) {
            return [$this->phpBinary(), 'composer.phar'];
        }

        return ['composer'];
    }

    /**
     * Get the PHP binary.
     *
     * @return string
     */
    protected function phpBinary()
    {
        return (new PhpExecutableFinder)->find(false);
    }

    /**
     * Get a new Symfony process instance.
     *
     * @param  array  $command
     * @return \Symfony\Component\Process\Process
     */
    protected function getProcess(array $command)
    {
        return new Process($command, $this->workingPath);
    }

    /**
     * Set the working path used by the class.
     *
     * @param  string  $path
     * @return $this
     */
    public function setWorkingPath($path)
    {
        $this->workingPath = realpath($path);

        return $this;
    }

    /**
     * Get working path used by the class.
     *
     * @return string
     */
    public function getWorkingPath()
    {
        return $this->workingPath;
    }
}
