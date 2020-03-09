<?php

use Laminas\Code\Generator\TypeGenerator;

require_once __DIR__.'/../vendor/autoload.php';

$type = TypeGenerator::fromTypeString('Request');


echo $type;