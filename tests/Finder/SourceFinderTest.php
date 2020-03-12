<?php

use Lake\Finder\SourceFinder;
use PHPUnit\Framework\TestCase;

class SourceFinderTest extends TestCase
{
    public function testFindType()
    {
        $finder = new SourceFinder();

        $result = $finder->findClass('Command');
    
        $this->assertIsArray($result);
    }

}