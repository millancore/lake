<?php

require_once __DIR__.'/../vendor/autoload.php';

$classes = array_filter(
    get_declared_classes(),
    function($className) {
        return call_user_func(
            array(new ReflectionClass($className), 'isInternal')
        );
    }
);

var_export($classes);