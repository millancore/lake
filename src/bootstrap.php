<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\Console\Application;

$application = new Application();

$application->add(new \Lake\Command\MakeCommand);
$application->add(new \Lake\Command\DumpCommand);

return $application;