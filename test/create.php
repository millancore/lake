<?php

require_once __DIR__.'/../vendor/autoload.php';

$class = new Laminas\Code\Generator\ClassGenerator();
$class->setName('World');

$class->setNamespaceName('Lake\Class');
$class->addUse('Laminas\Code\Generator\ClassGenerator');
$class->addUse('Laminas\Code\Generator\Request');

$file = new Laminas\Code\Generator\FileGenerator([
    'classes' => [$class]
]);


file_put_contents('World.php', $file->generate());
