<?php

namespace Lake\Generator;

use Exception;
use Lake\Config;
use Laminas\Code\Generator\ClassGenerator;
use Laminas\Code\Generator\FileGenerator;
use Laminas\Code\Generator\MethodGenerator;

class TestGenerator
{
    private $class;
    private $test;
    private $method;
    private $config;

    private $file;

    public function __construct(string $class, string $method, Config $config)
    {
        $this->class = $class;
        $this->method = $method;
        $this->config = $config;
     
        $this->init();
    }

    private function init()
    {

        $autoload = $this->config->src;
     
        $root = null;
        foreach ($autoload as $key => $value) {

            if (strpos($this->class, trim($key, '/')) !== false) {
                $root = trim($key, '/');
                break;
            }
        }

        $this->test = str_replace($root, $this->config->test['dir'], $this->class).'Test';
        $this->test = str_replace('.\\', '', $this->test);

        $testMethod = new MethodGenerator('test'.ucfirst($this->method));
        $testMethod->setBody(' ');

        if (!file_exists($this->config->executepath.DS.$this->test.'.php')) {

            $extendsClass  = $this->config->test['extends'];

            $testClass = new ClassGenerator(base_name($this->class).'Test');
            $testClass->setExtendedClass($extendsClass);
            $testClass->addUse($extendsClass);
            $testClass->addMethods([$testMethod]);

            $this->file = new FileGenerator([
                'class' => $testClass
            ]);

            return;
        } 

        $this->file = FileGenerator::fromReflectedFileName($this->test);

        $classes = $this->file->getClasses();

        if (count($classes) > 1) {
            throw new Exception(
                'This file contains more than one class, it cannot be modified by Lake'
            );
        }

        $this->class = current($classes);
        $testClass->addMethods([$testMethod]);

    }

    /**
     * Get FileGenerator Test
     *
     * @return FileGenerator
     */
    public function getFile() : FileGenerator
    {
        return $this->file;
    }


    /**
     * Get Test path
     *
     * @return string
     */
    public function getPath() : string
    {
        return $this->test;
    }

}