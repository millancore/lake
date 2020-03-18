<?php

namespace Lake\Generator;

use Laminas\Code\Generator\MethodGenerator as BaseMethodGenerator;

class MethodGenerator extends BaseMethodGenerator
{
    /**
     * @var null|TypeGenerator
     */
    private $returnType;

    /**
     * @var bool
     */
    private $returnsReference = false;

    /**
     * @return string
     */
    public function generate()
    {
        $output = '';

        $indent = $this->getIndentation();

        if (($docBlock = $this->getDocBlock()) !== null) {
            $docBlock->setIndentation($indent);
            $output .= $docBlock->generate();
        }

        $output .= $indent;

        if ($this->isAbstract()) {
            $output .= 'abstract ';
        } else {
            $output .= $this->isFinal() ? 'final ' : '';
        }

        $output .= $this->getVisibility()
            . ($this->isStatic() ? ' static' : '')
            . ' function '
            . ($this->returnsReference ? '& ' : '')
            . $this->getName() . '(';

        $parameters = $this->getParameters();
        if (!empty($parameters)) {
            foreach ($parameters as $parameter) {
                $parameterOutput[] = $parameter->generate();
            }

            $output .= implode(', ', $parameterOutput);
        }

        $output .= ')';

        if ($this->returnType) {
            $output .= ' : ' . $this->returnType->generate();
        }

        if ($this->isAbstract()) {
            return $output . ';';
        }

        if ($this->isInterface()) {
            return $output . ';';
        }

        $output .= self::LINE_FEED . $indent . '{' . self::LINE_FEED;

        if ($this->body) {
            $output .= preg_replace('#^((?![a-zA-Z0-9_-]+;).+?)$#m', $indent . $indent . '$1', trim($this->body))
                . self::LINE_FEED;
        }

        $output .= $indent . '}' . self::LINE_FEED;

        return $output;
    }

    /**
     * @param string|null $returnType
     *
     * @return MethodGenerator
     */
    public function setReturnType($returnType = null)
    {
        $this->returnType = null === $returnType
            ? null
            : TypeGenerator::fromTypeString($returnType);

        return $this;
    }

    /**
     * @return TypeGenerator|null
     */
    public function getReturnType()
    {
        return $this->returnType;
    }
}
