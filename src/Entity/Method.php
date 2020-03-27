<?php

namespace Lake\Entity;

use Lake\Exception\MethodException;
use Lake\Generator\MethodGenerator;
use Lake\Validation\NameValidation;

class Method
{
    const DEFAULT_NAME = '__construct';

    const VISIBILITY_PUBLIC    = 'public';
    const VISIBILITY_PROTECTED = 'protected';
    const VISIBILITY_PRIVATE   = 'private';

    private $name = self::DEFAULT_NAME;
    private $visibility = self::VISIBILITY_PUBLIC;

    private $returnType;
    private $parameters = [];
    private $docBlock;
    private $uses = [];
    
    public function __construct(
        string $name = null,
        array $parameters = [],
        string $returnType = null,
        string $docBlock = null
    )
    {
        $this->setName($name);
        $this->setParameters($parameters);
        $this->setReturnType($returnType);
        $this->setDocBlock($docBlock);
    }
    
    public function setName(string $name = null)
    {
        if(is_null($name)) {
            return;
        }
        
        NameValidation::validate($name);
        $this->name = $name;
    }

    public function setParameters(array $parameters)
    {
        foreach ($parameters as $parameter) {
            $this->parameters[] = Parameter::fromString($parameter);
        }

        return $this;
    }

    public function setReturnType(?string $returnType)
    {
        if (is_null($returnType)) {
            return;
        }

        if ($this->name == self::DEFAULT_NAME) {
            throw new MethodException('__construct() cannot declare a return type');
        }

        $this->returnType = new Type($returnType);
        return $this;
    }

    public function setVisibility(?string $visibility)
    {
        switch ($visibility) {
            case self::VISIBILITY_PRIVATE:
                $this->visibility = self::VISIBILITY_PRIVATE;
                break;
            case self::VISIBILITY_PROTECTED:
                $this->visibility = self::VISIBILITY_PROTECTED;
            break;
            case self::VISIBILITY_PUBLIC:
                $this->visibility = self::VISIBILITY_PUBLIC;
            break;
        }
    }

    public function setDocBlock(?string $docBlock)
    {
        $this->docBlock = $docBlock;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getParameters()
    {
        return $this->parameters;
    }

    public function getReturnType()
    {
        return $this->returnType;
    }

    public function getVisibility()
    {
        return $this->visibility;
    }

    public function getDocBlock()
    {
        return $this->docBlock;
    }

    public function getUses()
    {
        return $this->uses;
    }

    public function isEmptyConstruct()
    {
        return $this->name == self::DEFAULT_NAME && empty($this->parameters);
    }

    public function generate()
    {
        $method = new MethodGenerator($this->name);

        foreach ($this->parameters as $parameter) {
            $this->uses[] = $parameter->getUse();
            $method->setParameter($parameter->generate());
        }

        if ($this->returnType) {
            $this->uses[] = $this->returnType->getUse();
            $method->setReturnType($this->returnType->getName());
        }

        return $method->setBody(' ');
    }

}

