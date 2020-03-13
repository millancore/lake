<?php

use Lake\Finder\VendorFinder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

class VendorFinderTest extends TestCase
{
    public function testFindClass()
    {
        $finder = new VendorFinder(LAKE_ROOT . DS . 'cache/vendor.php');

        $result = $finder->findClass('VendorFinder');

        $this->assertEquals(['Lake\\Finder\\VendorFinder'], $result);
        $this->assertIsArray($result);
    }


    public function testFindeClassInvalidFileMap()
    {
        $this->expectException(FileNotFoundException::class);
        $this->expectExceptionMessage('Vendor class map no found!');

        $finder = new VendorFinder('invalid/path/to/vendorMapFile');
    }
}
