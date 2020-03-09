<?php

namespace Lake\Generator;

use Laminas\Code\Generator\ParameterGenerator as BaseParameterGenerator;

class ParameterGenerator extends BaseParameterGenerator
{
    /**
     * @param  string $type
     * @return ParameterGenerator
     */
    public function setType($type)
    {
        $this->type = TypeGenerator::fromTypeString($type);

        return $this;
    }
}