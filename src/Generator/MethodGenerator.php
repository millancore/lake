<?php

namespace Lake\Generator;

use Laminas\Code\Generator\DocBlockGenerator;
use Laminas\Code\Generator\MethodGenerator as BaseMethodGenerator;
use Laminas\Code\Reflection\MethodReflection;
use ReflectionMethod;

class MethodGenerator extends BaseMethodGenerator
{
    /**
     * @param  MethodReflection $reflectionMethod
     * @return MethodGenerator
     */
    public static function fromReflection(MethodReflection $reflectionMethod)
    {
        $method = static::copyMethodSignature($reflectionMethod);

        $method->setSourceContent($reflectionMethod->getContents(false));
        $method->setSourceDirty(false);

        if ($reflectionMethod->getDocComment() != '') {
            $method->setDocBlock(DocBlockGenerator::fromReflection($reflectionMethod->getDocBlock()));
        }

        $method->setBody(static::clearBodyIndention($reflectionMethod->getBody()));

        return $method;
    }

    /**
     * Returns a MethodGenerator based on a MethodReflection with only the signature copied.
     *
     * This is similar to fromReflection() but without the method body and phpdoc as this is quite heavy to copy.
     * It's for example useful when creating proxies where you normally change the method body anyway.
     */
    public static function copyMethodSignature(MethodReflection $reflectionMethod): BaseMethodGenerator
    {
        $method         = new static();
        $declaringClass = $reflectionMethod->getDeclaringClass();

        $method->setReturnType(self::extractReturnTypeFromMethodReflection($reflectionMethod));
        $method->setFinal($reflectionMethod->isFinal());

        if ($reflectionMethod->isPrivate()) {
            $method->setVisibility(self::VISIBILITY_PRIVATE);
        } elseif ($reflectionMethod->isProtected()) {
            $method->setVisibility(self::VISIBILITY_PROTECTED);
        } else {
            $method->setVisibility(self::VISIBILITY_PUBLIC);
        }

        $method->setInterface($declaringClass->isInterface());
        $method->setStatic($reflectionMethod->isStatic());
        $method->setReturnsReference($reflectionMethod->returnsReference());
        $method->setName($reflectionMethod->getName());

        foreach ($reflectionMethod->getParameters() as $reflectionParameter) {
            $method->setParameter(ParameterGenerator::fromReflection($reflectionParameter));
        }

        return $method;
    }

        /**
     * @param MethodReflection $methodReflection
     *
     * @return null|string
     */
    private static function extractReturnTypeFromMethodReflection(MethodReflection $methodReflection)
    {
        $returnType = method_exists($methodReflection, 'getReturnType')
            ? $methodReflection->getReturnType()
            : null;

        if (! $returnType) {
            return null;
        }

        if (! method_exists($returnType, 'getName')) {
            return self::expandLiteralType((string) $returnType, $methodReflection);
        }

        return ($returnType->allowsNull() ? '?' : '')
            . self::expandLiteralType($returnType->getName(), $methodReflection);
    }

        /**
     * @param string           $literalReturnType
     * @param ReflectionMethod $methodReflection
     *
     * @return string
     */
    private static function expandLiteralType($literalReturnType, ReflectionMethod $methodReflection)
    {
        if ('self' === strtolower($literalReturnType)) {
            return $methodReflection->getDeclaringClass()->getName();
        }

        if ('parent' === strtolower($literalReturnType)) {
            return $methodReflection->getDeclaringClass()->getParentClass()->getName();
        }

        return $literalReturnType;
    }

}
