<?php

use Lake\Finder\Finder;
use PHPUnit\Framework\TestCase;

class FinderTest extends TestCase
{

    public function testFindClass()
    {
        $result = Finder::findClassByName('Closure'); 

        $this->assertEquals(['Closure'], $result);
        $this->assertIsArray($result);
    }
    
}