<?php

namespace Lake\Entity;

use Lake\Contract\GeneratorInterface;
use Lake\Exception\LakeException;
use Lake\Finder\GlobalFinder;
use Laminas\Code\Generator\ClassGenerator;
use Laminas\Code\Generator\FileGenerator;

class LakeClass implements GeneratorInterface
{
    const EXTENSION = '.php'; 

    private $name;
    private $namespace;
    private $path;
    private $exists = false;

    /** @var Method */
    private $method;
    private $extends;
    private $uses = [];

    /** @var FileGenerator */
    private $file;

    public function __construct(string $path, ?string $extends = null)
    {
       $this->resolvePath($path);
       $this->setExtendedClass($extends);
    }

    private function resolvePath($path)
    {
        if (!strpos($path, ':') === false) {
            $path = str_replace(':', DS, $path);            
        }

        if (isset(pathinfo($path)['extension'])) {
            $path = preg_replace('/\\.[^.\\s]{3}$/', '', $path);
        }

        if (file_exists($path.self::EXTENSION)) {
            $this->exists = true;
        }
        
        $this->name = base_name($path);
        $this->path = $path;
    }

    public function setExtendedClass(?string $extends)
    {
        if (is_null($extends)) {
            return;
        }

        if ($this->exists ) {
            throw new LakeException('Invalid extends on existing class');
        }

        $found = GlobalFinder::findUse($extends);

        if (!is_null($found)) {
            $this->uses[] = $this->extends = $found;
            return;
        }

        $this->extends = $extends;
    }

    public function setNamespace(string $namespace)
    {
        $this->namespace = $namespace;
    }

    public function getNamespace()
    {
        return $this->namespace;
    }

    public function addUse(string $use)
    {
        $this->uses[] = $use;
    }

    public function addMethod(Method $method)
    {
        $this->method = $method;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getFullPath()
    {
        return $this->path.self::EXTENSION;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function getName()
    {
        return $this->name;
    }

    public function exists()
    {
        return $this->exists;
    }

    public function generate()
    {
        $file = $this->createFile();
        $class = $file->getClass($this->name);

        if ($this->method) {
            $method = $this->method->generate();
            $class->addMethods([$method]);
        }

        $this->addUses($class);
        $class->setExtendedClass($this->extends);

        return $file->generate();
    }

    private function createFile()
    {
        if (!$this->exists) {
            return new FileGenerator([
                'class' =>  new ClassGenerator($this->name, $this->namespace) 
            ]);
        }

        $file = FileGenerator::fromReflectedFileName(
            $this->path.self::EXTENSION
        );

        if (count($file->getClasses()) > 1) {
            throw new LakeException(
                'This file contains more than one class, it cannot be modified by Lake'
            );
        }

        return $file;
    }

    private function addUses(ClassGenerator $class)
    {
        $uses = $this->uses;

        if($this->method) {
            $uses = array_merge($this->method->getUses(), $uses);
        }

        foreach ($uses as $use) {
            if (!$class->hasUse($use) && !is_null($use)) {
                $class->addUse($use);
            }
        }
    }

}
