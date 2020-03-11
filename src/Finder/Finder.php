<?php

namespace Lake\Finder;

use Lake\Contract\FinderInterface;

class Finder implements FinderInterface
{
    private $finders;

    public function __construct()
    {
        $this->finders = [
          new InternalFinder,
          new SourceFinder,
          new VendorFinder
        ];
        
    }

    public static function findClassByName(string $className) : array
    {
        return (new self)->findClass($className);
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