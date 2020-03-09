<?php

require_once __DIR__.'/../vendor/autoload.php';

$findClass = 'Closuree';

$declaredClasses = get_declared_classes();


$classes = array_filter(
    $declaredClasses,
    function($className) use ($findClass) {
        return call_user_func([new ReflectionClass($className), 'isInternal']) && $className == $findClass;
    }
);

echo current($classes);