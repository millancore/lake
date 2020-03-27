<?php

namespace Lake\Entity;

use Lake\Finder\GlobalFinder;
use Lake\Validation\TypeValidation;

class Type
{
    private $name;
    private $internal = false;
    private $use;

    public function __construct(string $name)
    {
        $this->setType($name);
    }

    public function setType(string $name)
    {
        TypeValidation::validate($name);

        if (!TypeValidation::isPhpType($name)) {
            $this->use = GlobalFinder::findUse($name);
        }

        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getUse()
    {
        return $this->use;
    }

    public function isInternal()
    {
        return $this->internal;
    }
}


