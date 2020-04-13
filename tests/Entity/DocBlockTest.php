<?php

use Lake\Entity\DocBlock;
use Lake\Entity\Tag;
use Laminas\Code\Generator\DocBlockGenerator;
use PHPUnit\Framework\TestCase;

class DocBlockTest extends TestCase
{
    public function testGenerateDocBlockWhitDescription()
    {
        $docblock = new DocBlock('Helpful funtion');

        $docblock->addTag(Tag::TYPE_RETURN, '$request', ['array']);

        $this->assertInstanceOf(DocBlockGenerator::class, $docblock->generate());
    }
}