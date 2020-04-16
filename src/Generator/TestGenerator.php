<?php

namespace Lake\Generator;

use Lake\Config;
use Lake\Entity\LakeClass;
use Lake\Entity\Method;

class TestGenerator
{
    const PREFIX_METHOD = 'testDummy';
    const POSTFIX_NAME = 'Test';

    private $class;
    private $test;

    private $config;

    public function __construct(LakeClass $class, Config $config)
    {
        $this->class = $class;
        $this->config = $config->test;
        $this->init();
    }

    private function init()
    {
        $root = explode(DS, $this->class->getPath());

        $testPath = str_replace(
            current($root),
            $this->config['dir'], 
            $this->class->getPath()
        );

        $this->test = new LakeClass($testPath.self::POSTFIX_NAME);

        if (!$this->test->exists()) {
            $this->test->setExtendedClass($this->config['extends']);
            
            $this->test->addUse(
                $this->class->getNamespace().'\\'.$this->class->getName()
            );
        }

        $classMethod = $this->class->getMethod();

        if($classMethod->getName() == $classMethod::DEFAULT_NAME) {
            return;
        }

        $name = self::PREFIX_METHOD.ucfirst($classMethod->getName());

        if ($this->config['style'] == 'snake') {
            $name = snake($name);
        }

        $this->test->addMethod(new Method($name));
    }

    public function getTest()
    {
        return $this->test;
    }
}
