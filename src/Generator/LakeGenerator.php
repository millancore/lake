<?php

namespace Lake\Generator;

use Laminas\Code\Generator\ClassGenerator;
use Laminas\Code\Generator\MethodGenerator;

class LakeGenerator {
    
    private $class;

    public function __construct()
    {
        $this->class = new ClassGenerator();
    }


    public function generate(String $className)
    {
        $this->class->setName($className);

        return $this->class;
    }

    public function addMethod(String $name, $params = [] )
    {
        $method = new MethodGenerator($name);

        $method->setParameter(new ParameterGenerator('command', 'Command', null));
        $method->setParameter(new ParameterGenerator('id', 'int'));

        $method->setBody(' ');


        $this->class->addMethods([$method]);
    }
}