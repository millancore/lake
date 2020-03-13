<?php

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

define('LAKE_ROOT', realpath(__DIR__));
define('LAKE_CACHE', LAKE_ROOT.DS.'cache');


$possibleAutoloadFiles = [
    LAKE_ROOT.'/../../autoload.php',
    LAKE_ROOT.'/../autoload.php',
    LAKE_ROOT.'/vendor/autoload.php'
];

$autoloadFile = null;
foreach ($possibleAutoloadFiles as $possibleFile) {
    if (file_exists($possibleFile)) {
        $autoloadFile = $possibleFile;
        break;
    }
}

if (null === $autoloadFile) {
    throw new RuntimeException('Unable to locate autoload.php file.');
}

define('AUTOLOAD_PATH', $autoloadFile);

require_once $autoloadFile;
