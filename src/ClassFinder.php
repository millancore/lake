<?php

namespace Lake;

class ClassFinder
{

    private $composerStaticInit = "vendor/composer/autoload_static.php";
    private $classMap;

    public function __construct()
    {
        
    }

    public function findClass(String $className): array
    {
        # Load class Map
        $this->loadClassMap();

        $pfix = "\\$className";
        $len = strlen($pfix);

        $list = [];

        foreach ($this->classMap as $class => $path) {
            if (substr_compare($class, $pfix, -$len, $len) === 0) {
                $list[] = $class;
            }
        }

        return $list;
    }

    private function loadClassMap()
    {
        $fp = fopen($this->composerStaticInit, 'r');

        $staticClass = $buffer = '';
        $i = 0;
        while (!$staticClass) {
            if (feof($fp)) break;

            $buffer = fread($fp, 150);
            $tokens = token_get_all($buffer);

            if (strpos($buffer, '{') === false) continue;

            for (; $i < count($tokens); $i++) {
                if ($tokens[$i][0] === T_CLASS) {
                    for ($j = $i + 1; $j < count($tokens); $j++) {
                        if ($tokens[$j] === '{') {
                            $staticClass = $tokens[$i + 2][1];
                        }
                    }
                }
            }
        }

        $staticClass = '\\Composer\\Autoload\\'.$staticClass;
        $this->classMap = $staticClass::$classMap;
    }
}
