<?php

use Lake\Config;
use Lake\Finder\Finder;
use PHPUnit\Framework\TestCase;

class FinderTest extends TestCase
{

    public function testFindClass()
    {
        $result = Finder::findClassByName('Closure', new Config); 

        $this->assertEquals(['Closure'], $result);
        $this->assertIsArray($result);
    }
    
}