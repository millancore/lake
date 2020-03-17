<?php

use Lake\Config;
use Lake\Finder\SourceFinder;
use PHPUnit\Framework\TestCase;

class SourceFinderTest extends TestCase
{
    public function testFindType()
    {
        $finder = new SourceFinder((new Config)->src);

        $result = $finder->findClass('Command');
    
        $this->assertIsArray($result);
    }

}