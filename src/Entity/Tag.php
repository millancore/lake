<?php

namespace Lake\Entity;

use Lake\Exception\LakeException;
use Laminas\Code\Generator\DocBlock\Tag\ParamTag;
use Laminas\Code\Generator\DocBlock\Tag\ReturnTag;

class Tag
{
    const TYPE_PARAM = 'param';
    const TYPE_RETURN = 'return';

    private $tag;

    private $types = [];
    private $name;
    private $description;

    public function __construct(
        string $tag = null,
        string $name = null,
        array $types = [],
        string $description = null
    )
    {
        $this->setTag($tag);
        $this->setName($name);
        $this->setTypes($types);
        $this->setDescription($description);
    }

    public function setTag(string $tag)
    {
        switch ($tag) {
            case self::TYPE_PARAM:
                $this->tag = self::TYPE_PARAM;
                break;
            case self::TYPE_RETURN:
                $this->tag = self::TYPE_RETURN;
                break;
            default:
                throw new LakeException('Invalid Tag for docBlock');
                break;
        }
    }

    public function setName(string $name)
    {
        if ($this->tag == self::TYPE_RETURN) {
            $this->name = null;
        }

        $this->name = $name;

    }

    public function setTypes(array $types)
    {
        if ($this->tag == self::TYPE_RETURN && count($types) > 1) {
            throw new LakeException('Can not posible have multiple return types');
        }

        $this->types = $types;
    }

    public function setDescription(?string $description)
    {
        $this->description = $description;
    }

    public function generate()
    {
        switch ($this->tag) {
            case self::TYPE_PARAM:
                return new ParamTag($this->name, $this->types, $this->description);
                break;
            
            case self::TYPE_RETURN:
                return new ReturnTag($this->types, $this->description);
                break;
        }
    }
}