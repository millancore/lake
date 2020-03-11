<?php

namespace Lake\Generator;

use Laminas\Code\Generator\ClassGenerator;
use Laminas\Code\Generator\MethodGenerator;

class LakeGenerator {
    
    private $path;
    private $namespace;
    private $method;
    private $parameters;
    private $uses;

    /** @var ClassGenerator */
    private $class;

    public function __construct(
        string $path,
        string $namespace,
        string $method,
        array $parameters,
        array $uses
    )
    {
        $this->path = $path;
        $this->namespace = $namespace;
        $this->method = $method;
        $this->parameters = $parameters;
        $this->uses = $uses;
    }

    public function getClass()
    {
        $this->class = new ClassGenerator;
        $this->class->setName(basename($this->path));
        $this->class->setNamespaceName($this->namespace);

        $this->addMethod($this->method, $this->parameters);
        $this->addUses($this->uses);

        return $this->class;
    }

    public function getTest()
    {
        $test = new ClassGenerator;
        $test->setName(basename($this->path).'Test');
        $test->setExtendedClass('TestCase');


        $testMethod = new MethodGenerator('test'.ucfirst($this->method));
        $testMethod->setBody(' ');

        $test->addMethods([$testMethod]);
        $test->addUse('PHPUnit\Framework\TestCase');

        return $test;
    }

    private function addMethod(string $name, $parameters = [] )
    {
        $method = new MethodGenerator($name);

        foreach ($parameters as $parameter) {
            $method->setParameter(new ParameterGenerator($parameter[1], $parameter[0]));
        }

        $method->setBody(' ');
        $this->class->addMethods([$method]);
    }

    private function addUses(array $uses)
    {
        foreach ($uses as $use) {
            $this->class->addUse($use);
        }
    }
}