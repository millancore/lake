<?php

namespace Lake\Resolver;

use Lake\Config;

class NamespaceResolver
{
    public static function resolve(string $path, Config $config)
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $path = str_replace('.\\','', $path);
        }

        $classPath = str_replace('/', '\\', $path);

        $namespace = null;
        foreach ($config->src as $key => $value) {

            $key = trim($key, '/');
            if (strpos($classPath, $key) !== false) {
                $namespace = str_replace($key, trim($value, '\\'), $classPath);
                break;
            }
        }
        
        return $namespace;
    }
}
