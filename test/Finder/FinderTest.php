<?php

use Lake\Finder\Finder;
use PHPUnit\Framework\TestCase;

class FinderTest extends TestCase
{

    public function testFindClass()
    {
        $result = Finder::findClassByName('ParameterGenerator'); 

        var_export($result);
        die;

        $this->assertEquals(['Closure'], $result);
        $this->assertIsArray($result);
    }
    
}