<?php

namespace Lake\Generator;

use Exception;
use Laminas\Code\Generator\ClassGenerator;
use Laminas\Code\Generator\DocBlock\Tag;
use Laminas\Code\Generator\DocBlockGenerator;
use Laminas\Code\Generator\FileGenerator;

class LakeGenerator
{
    private $exists;
    private $classPath;
    private $autoload;
    private $namespace;

    /** @var ClassGenerator */
    private $class;

    /** @var FileGenerator */
    private $file;

    public function __construct(bool $exists, string $classPath, array $autoload)
    {
        $this->exists = $exists;
        $this->classPath = $classPath;
        $this->autoload = $autoload;

        $this->init();
    }

    /**
     * Resolve namespace from route 
     * @return null|string
     */
    private function namespaceResolver(): ?string
    {
        $classPath = str_replace('/', '\\', realpath($this->classPath));

        $namespace = null;
        foreach ($this->autoload as $key => $value) {

            $key = trim($key, '/');
            if (strpos($classPath, $key) !== false) {
                $namespace = str_replace($key, trim($value, '\\'), $classPath);
                break;
            }
        }

        return $namespace;
    }

    /**
     * Initialize Class and File Generators
     * @return void
     */
    private function init()
    {
        $this->namespace = $this->namespaceResolver();

        if (!$this->exists) {

            $this->class = new ClassGenerator(base_name($this->classPath), baseclass($this->namespace));
            $this->file = new FileGenerator(['class' => $this->class]);

            return;
        }

        $this->file = FileGenerator::fromReflectedFileName(
            $this->classPath . '.php'
        );

        $classes = $this->file->getClasses();

        if (count($classes) > 1) {
            throw new Exception(
                'This file contains more than one class, it cannot be modified by Lake'
            );
        }

        $this->class = current($classes);
        $this->restoreParameters();
    }


    /**
     * Get Class
     * @return ClassGenerator
     */
    public function getClass(): ClassGenerator
    {
        return $this->class;
    }


    /**
     * Get File With Class
     * @return FileGenerator
     */
    public function getFile(): FileGenerator
    {
        return $this->file;
    }

    /**
     * Add method to current class
     *
     * @param string $name
     * @param array $parameters
     * @return void
     */
    public function addMethod(string $name, $parameters = [], string $return = 'void', string $docBlock = null)
    {
        $method = new MethodGenerator($name);

        $docBlockTags = [];
        foreach ($parameters as $parameter) {
            $method->setParameter(new ParameterGenerator($parameter[1], $parameter[0]));
            $docBlockTags[] = new Tag\ParamTag($parameter[1], $parameter[0]);
        }

        if ($name != '__construct') {
            $method->setReturnType($return);
            $docBlockTags[] = new Tag\ReturnTag($return);
        }

        if (!empty($parameters) || !is_null($docBlock) ) {
            $method->setDocBlock(
                new DocBlockGenerator($docBlock, null, $docBlockTags)
            );
        }


        $method->setBody(' ');
        $this->class->addMethods([$method]);
    }

    /**
     * Add uses to current class
     *
     * @param array $uses
     * @return void
     */
    public function addUses(array $uses)
    {
        foreach ($uses as $use) {
            if (!$this->class->hasUse($use) && !is_null($use)) {
                $this->class->addUse($use);
            }
        }
    }

    /**
     * Set extends class
     *
     * @param string|null $extends
     * @return void
     */
    public function setExtends(?string $extends)
    {
        if (is_null($extends)) {
            return;
        }

        if ($this->exists) {
            throw new Exception('Invalid extends to existing class');
        }

        $this->class->setExtendedClass($extends);
    }

    /**
     * Restore Type Parameter
     *
     * @return void
     */
    private function restoreParameters()
    {
        $methods = $this->class->getMethods();

        foreach ($methods as $method) {
            $params = $method->getParameters();

            foreach ($params as $param) {

                $type = $param->getType();
                $newParameter = new ParameterGenerator($param->getName(), base_name($type));
                $method->setParameter($newParameter);
            }

            if (!is_null($method->getReturnType())) {
                $method->setReturnType(base_name($method->getReturnType()));
            }
        }
    }
}
