<?php

namespace Lake\Generator;

use Laminas\Code\Generator\ParameterGenerator as BaseParameterGenerator;
use Laminas\Code\Reflection\ParameterReflection;
use ReflectionParameter;

class ParameterGenerator extends BaseParameterGenerator
{

    /**
     * @param  ParameterReflection $reflectionParameter
     * @return ParameterGenerator
     */
    public static function fromReflection(ParameterReflection $reflectionParameter)
    {
        $param = new self();

        $param->setName($reflectionParameter->getName());

        if ($type = self::extractFQCNTypeFromReflectionType($reflectionParameter)) {
            $param->setType($type);
        }

        $param->setPosition($reflectionParameter->getPosition());

        $variadic = method_exists($reflectionParameter, 'isVariadic') && $reflectionParameter->isVariadic();

        $param->setVariadic($variadic);

        if (!$variadic && ($reflectionParameter->isOptional() || $reflectionParameter->isDefaultValueAvailable())) {
            try {
                $param->setDefaultValue($reflectionParameter->getDefaultValue());
            } catch (\ReflectionException $e) {
                $param->setDefaultValue(null);
            }
        }

        $param->setPassedByReference($reflectionParameter->isPassedByReference());

        return $param;
    }

    /**
     * @param ParameterReflection $reflectionParameter
     *
     * @return null|string
     */
    private static function extractFQCNTypeFromReflectionType(ParameterReflection $reflectionParameter)
    {
        if (!method_exists($reflectionParameter, 'getType')) {
            return self::prePhp7ExtractFQCNTypeFromReflectionType($reflectionParameter);
        }

        $type = method_exists($reflectionParameter, 'getType')
            ? $reflectionParameter->getType()
            : null;

        if (!$type) {
            return null;
        }

        if (!method_exists($type, 'getName')) {
            return self::expandLiteralParameterType((string) $type, $reflectionParameter);
        }

        return ($type->allowsNull() ? '?' : '')
            . self::expandLiteralParameterType($type->getName(), $reflectionParameter);
    }

    /**
     * For ancient PHP versions (yes, you should upgrade to 7.0):
     *
     * @param ParameterReflection $reflectionParameter
     *
     * @return string|null
     */
    private static function prePhp7ExtractFQCNTypeFromReflectionType(ParameterReflection $reflectionParameter)
    {
        if ($reflectionParameter->isCallable()) {
            return 'callable';
        }

        if ($reflectionParameter->isArray()) {
            return 'array';
        }

        if ($class = $reflectionParameter->getClass()) {
            return $class->getName();
        }

        return null;
    }

    /**
     * @param string              $literalParameterType
     * @param ReflectionParameter $reflectionParameter
     *
     * @return string
     */
    private static function expandLiteralParameterType($literalParameterType, ReflectionParameter $reflectionParameter)
    {
        if ('self' === strtolower($literalParameterType)) {
            return $reflectionParameter->getDeclaringClass()->getName();
        }

        if ('parent' === strtolower($literalParameterType)) {
            return $reflectionParameter->getDeclaringClass()->getParentClass()->getName();
        }

        return $literalParameterType;
    }


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
