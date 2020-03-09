<?php

namespace Lake\Finder;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveRegexIterator;
use RegexIterator;

class SourceFinder
{
    private $src;

    public function __construct()
    {
        $dir = getcwd().DIRECTORY_SEPARATOR.'src';

        $directory = new RecursiveDirectoryIterator($dir);
        $iterator = new RecursiveIteratorIterator($directory);

        $regex = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);
 
        $classes = [];
        foreach ($regex as $info) {
            preg_match('/([A-Z]\w+).php/', current($info), $matches);
            if (isset($matches[1])) {
                $classes[$matches[1]][] = str_replace($dir, 'Lake', current($info));
            }
        }

        var_export($classes);

    }

    public function findType(string $type)
    {
        
    }
}