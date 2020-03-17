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

    public function __construct(array $autoload)
    {
        $classMap = [];
        foreach ($autoload as $dir => $namespace) {
           $classMap = array_merge($classMap, $this->loadClassMap($dir, $namespace));
        }

        $this->classMap = $classMap;
    }

    public function findClass(String $className) : array
    {
        if(isset($this->classMap[$className])) {
            return $this->classMap[$className];
        }

        return [];
    }

    private function loadClassMap($dir, $namespace) : array
    {
        $directory = new RecursiveDirectoryIterator($dir);
        $iterator = new RecursiveIteratorIterator($directory);

        $regex = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);
 
        $classes = [];
        foreach ($regex as $info) {

            preg_match('/([A-Z]\w+).php/', current($info), $matches);

            if (isset($matches[1])) {

                $class = str_replace($matches[0], $matches[1], current($info));
                $classes[$matches[1]][] = str_replace('/', '\\',str_replace($dir, $namespace, $class));
            }
        }

        return $classes;
    }

}