<?php

namespace Lake\Generator;

class TestGenerator
{

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

}