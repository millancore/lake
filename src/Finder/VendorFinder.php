<?php

namespace Lake\Finder;

use Lake\Contract\FinderInterface;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

class VendorFinder implements FinderInterface
{
    private $classMap;

    public function __construct()
    {
        $this->loadClassMap();
    }

    public function findClass(String $className) : array
    {
        if(isset($this->classMap[$className])) {
            return $this->classMap[$className];
        }

        return [];
    }

    private function loadClassMap()
    {
        $vendorFileClassMap = LAKE_ROOT.DIRECTORY_SEPARATOR.'cache/vendor.php';

        if(!file_exists($vendorFileClassMap)) {
            throw new FileNotFoundException('Vendor class map no found!');
        }

        $this->classMap = include $vendorFileClassMap;
    }
}