<?php

namespace Lake\Generator;

use Laminas\Code\Generator\MethodGenerator;
use Laminas\Code\Reflection\ClassReflection;

class LakeGenerator {
    
    private $exists;
    private $classPath;
    private $autoload;
    private $namespace;

    /** @var ClassGenerator */
    private $class;

    public function __construct(bool $exists, string $classPath, array $autoload)
    {
        $this->exists = $exists;
        $this->classPath = $classPath;
        $this->autoload = $autoload;

        $this->namespace = $this->namespaceResolver();

        if (!$this->exists) {
            $this->class = new ClassGenerator(
                base_name($this->classPath),
                dirname($this->namespace)
            );
        } else {
            $this->class = ClassGenerator::fromReflection(
                new ClassReflection('\\'.$this->namespace)
            );
        }
    }

    private function namespaceResolver()
    {
        $namespace = str_replace(
            key($this->autoload), current($this->autoload), $this->classPath
        );

        return $namespace;
    }


    public function getClass()
    {
        return $this->class;
    }

    public function addMethod(string $name, $parameters = [] )
    {
        $method = new MethodGenerator($name);

        foreach ($parameters as $parameter) {
            $method->setParameter(new ParameterGenerator($parameter[1], $parameter[0]));
        }

        $method->setBody(' ');
        $this->class->addMethods([$method]);
    }

    public function addUses(array $uses)
    {
        foreach ($uses as $use) {
            if (!$this->class->hasUse($use)) {
                $this->class->addUse($use);
            }
        }
    }
}