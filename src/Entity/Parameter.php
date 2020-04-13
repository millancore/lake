<?php

namespace Lake\Entity;

use Lake\Generator\ParameterGenerator;
use Lake\Validation\NameValidation;
use Lake\Validation\TypeValidation;

class Parameter
{
    /** @var Type */
    private $type;
    private $name;

    public function __construct(string $type, string $name)
    {
        $this->setType($type);
        $this->setName($name);
    }

    public static function fromString(string $parameter)
    {
        if (!strpos($parameter, ':') === false) {
            list($type, $name) = explode(':', $parameter);
        } else {
            $type = $parameter;
            $name = lcfirst(TypeValidation::clear($parameter));
        }
    
        return new self($type, $name);
    }

    public function setType(string $type)
    {
        $this->type = new Type($type);
    }

    public function setName(string $name)
    {
        NameValidation::validate($name);
        $this->name = $name;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getUse()
    {
        return $this->type->getUse();
    }

    public function generate()
    {
        return new ParameterGenerator($this->name, $this->type->getName());
    }

}

