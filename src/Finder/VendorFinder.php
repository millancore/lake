<?php

namespace Lake\Finder;

use Lake\Contract\FinderInterface;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

class VendorFinder implements FinderInterface
{
    private $classMap;

    public function __construct(string $vendorFileClassMap)
    {
        $this->loadClassMap($vendorFileClassMap);
    }

    /**
     * Find class present in vendor Composer
     *
     * @param String $className
     * @return array
     */
    public function findClass(String $className) : array
    {
        if(isset($this->classMap[$className])) {
            return $this->classMap[$className];
        }

        return [];
    }

    /**
     * Load from vendor dump
     *
     * @param string $vendorFileClassMap
     * @return void
     */
    private function loadClassMap($vendorFileClassMap)
    {

        if(!file_exists($vendorFileClassMap)) {
            throw new FileNotFoundException('Vendor class map no found!');
        }

        $this->classMap = include $vendorFileClassMap;
    }
}
