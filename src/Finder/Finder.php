<?php

namespace Lake\Finder;

use Lake\Config;
use Lake\Contract\FinderInterface;

class Finder implements FinderInterface
{
    private $finders;

    public function __construct(Config $config)
    {
        $vendorFileClassMap = LAKE_ROOT.DS.'cache/vendor.php';

        $this->finders = [
          new InternalFinder,
          new SourceFinder($config->src),
          new VendorFinder($vendorFileClassMap)
        ];
        
    }

    public static function findClassByName(string $className, Config $config) : array
    {
        return (new self($config))->findClass($className);
    }


    public function findClass(string $className) : array
    {
        $result = [];
        foreach ($this->finders as $finder) {
            $classes = $finder->findClass($className);

            if (empty($classes)) {
                continue;
            }

            $result = array_merge($result, array_values($classes));
        }

        return array_values(array_unique($result, SORT_NATURAL));
    }
}