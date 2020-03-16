<?php

namespace Lake\Generator;

use Laminas\Code\Generator\GeneratorInterface;

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

    /**
     * @return string the cleaned type string
     */
    public function __toString()
    {
        return ltrim($this->generate(), '?\\');
    }


    public function generate()
    {
        return  $this->type;
    }
}
