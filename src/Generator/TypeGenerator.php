<?php

namespace Lake\Generator;

use Laminas\Code\Generator\GeneratorInterface;
use Laminas\Code\Generator\Exception\InvalidArgumentException;


final class TypeGenerator implements GeneratorInterface
{

    private $type;
    private $nullable;

    private function __construct()
    {
    }

    public static function fromTypeString($type)
    {
        $instance = new self();
        $instance->type = $type;

        return $instance;
    }

    
    public function generate()
    {
        return  $this->type;
    }

}
