<?php

use Lake\Finder\VendorFinder;
use PHPUnit\Framework\TestCase;

class VendorFinderTest extends TestCase
{
    public function testFindClass()
    {
        $finder = new VendorFinder;

        $this->assertIsArray($finder->findClass('Exception'));
    }
}