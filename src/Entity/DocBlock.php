<?php

namespace Lake\Entity;

use Laminas\Code\Generator\DocBlockGenerator;

class DocBlock
{
    private $description = 'Undocumented function';
    private $tags = [];

    public function __construct(string $description = null)
    {
        if(!is_null($description)){
            $this->description = $description;
        }
    } 

    public function addTag(string $tag, string $name, array $types, string $description = null)
    {
        $this->tags[] = new Tag($tag, $name, $types, $description);
    }

    public function generate()
    {
        $docBlock = new DocBlockGenerator($this->description);

        foreach ($this->tags as $tag) {
            $docBlock->setTag($tag->generate());
        }

        return $docBlock;
    }

}