<?php

namespace Lake\Console;

use Lake\Config;
use Lake\Process\ProcessManager;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

class Application extends BaseApplication
{
    const VERSION = '0.0.1';

    private $config;
    private $processManager;

    public function __construct(string $executePath)
    {
        parent::__construct('Lake, Code Mirror', self::VERSION);
        $this->config = new Config($this->loadFromConfigFile($executePath));
        $this->processManager = new ProcessManager(new Filesystem);

        $this->registerProcess();
        $this->registerCommands();
    }

    private function loadFromConfigFile($executePath)
    {
        $configFile = $executePath.DS.'lake.yml';

        $optionsFromFile = [];
        if (file_exists($configFile)) {
           $optionsFromFile = Yaml::parseFile($configFile);
        }
        
        return $optionsFromFile; 
    }


    private function registerProcess()
    {
        $this->processManager->addComposerCommand('dump', ['dump-autoload']);
        $this->processManager->addComposerCommand('optimize',[
            'dump-autoload', '--optimize', '--no-dev'
        ]);
        $this->processManager->addPhpCommand('map', [
            LAKE_ROOT . '/src/Process/export', AUTOLOAD_PATH, LAKE_CACHE
        ]);
    }


    private function registerCommands()
    {
        $this->add(new \Lake\Command\MakeCommand($this->config));
        $this->add(new \Lake\Command\DumpCommand($this->processManager));
    }

}
