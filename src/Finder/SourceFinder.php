<?php

namespace Lake\Finder;

use Lake\Contract\FinderInterface;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveRegexIterator;
use RegexIterator;

class SourceFinder implements FinderInterface
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
        $dir = getcwd().DIRECTORY_SEPARATOR.'src';

        $directory = new RecursiveDirectoryIterator($dir);
        $iterator = new RecursiveIteratorIterator($directory);

        $regex = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);
 
        $classes = [];
        foreach ($regex as $info) {
            preg_match('/([A-Z]\w+).php/', current($info), $matches);
            if (isset($matches[1])) {
                $class = str_replace($matches[0], $matches[1], current($info));
                $classes[$matches[1]][] = str_replace($dir, 'Lake', $class);
            }
        }

        $this->classMap = $classes;
    }

}