<?php

namespace Lake\Generator;

use Lake\Validation\TypeValidation;
use Laminas\Code\Generator\ClassGenerator as BaseClassGenerator;
use Laminas\Code\Generator\DocBlockGenerator;
use Laminas\Code\Generator\PropertyGenerator;
use Laminas\Code\Reflection\ClassReflection;

class ClassGenerator extends BaseClassGenerator
{
     /**
     * Build a Code Generation Php Object from a Class Reflection
     *
     * @param  ClassReflection $classReflection
     * @return self
     */
    public static function fromReflection(ClassReflection $classReflection)
    {
        $cg = new static($classReflection->getName());

        $cg->setSourceContent($cg->getSourceContent());
        $cg->setSourceDirty(false);

        if ($classReflection->getDocComment() != '') {
            $cg->setDocBlock(DocBlockGenerator::fromReflection($classReflection->getDocBlock()));
        }

        $cg->setAbstract($classReflection->isAbstract());

        // set the namespace
        if ($classReflection->inNamespace()) {
            $cg->setNamespaceName($classReflection->getNamespaceName());
        }

        /* @var \Laminas\Code\Reflection\ClassReflection $parentClass */
        $parentClass = $classReflection->getParentClass();
        $interfaces  = $classReflection->getInterfaces();

        if ($parentClass) {
            $cg->setExtendedClass($parentClass->getName());

            $interfaces = array_diff($interfaces, $parentClass->getInterfaces());
        }

        $interfaceNames = [];
        foreach ($interfaces as $interface) {
            /* @var \Laminas\Code\Reflection\ClassReflection $interface */
            $interfaceNames[] = $interface->getName();
        }

        $cg->setImplementedInterfaces($interfaceNames);

        $properties = [];

        foreach ($classReflection->getProperties() as $reflectionProperty) {
            if ($reflectionProperty->getDeclaringClass()->getName() == $classReflection->getName()) {
                $properties[] = PropertyGenerator::fromReflection($reflectionProperty);
            }
        }

        $cg->addProperties($properties);

        $constants = [];

        foreach ($classReflection->getConstants() as $name => $value) {
            $constants[] = [
                'name' => $name,
                'value' => $value,
            ];
        }

        $cg->addConstants($constants);

        $methods = [];

        foreach ($classReflection->getMethods() as $reflectionMethod) {
            $className = $cg->getNamespaceName() ? $cg->getNamespaceName() . '\\' . $cg->getName() : $cg->getName();

            if ($reflectionMethod->getDeclaringClass()->getName() == $className) {
                $methods[] = MethodGenerator::fromReflection($reflectionMethod);
            }
        }

        $cg->addMethods($methods);
        $cg->restoreUses($cg, $methods);
        
        return $cg;
    }


    private function restoreUses(ClassGenerator $classGenerator, array $methods)
    {
        foreach ($methods as $method) {
            $params = $method->getParameters();
            
            foreach ($params as $param) {
                $type = $param->getType();
                
                if (!TypeValidation::isPhpType($type)) {
                    $param->setType(base_name($type));
                    $classGenerator->addUse($type);
                }
            }   
        }

    }




}