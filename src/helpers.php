<?php

/**
 * basename compatible with windows and unix
 */
function base_name($path)
{
    if (preg_match('@^.*[\\\\/]([^\\\\/]+)$@s', $path, $matches)) {
        return $matches[1];
    } else if (preg_match('@^([^\\\\/]+)$@s', $path, $matches)) {
        return $matches[1];
    }
    return '';
}


function snake($value, $delimiter = '_')
{
    if (!ctype_lower($value)) {
        $value = preg_replace('/\s+/u', '', ucwords($value));

        $value = mb_strtolower(preg_replace('/(.)(?=[A-Z])/u', '$1' . $delimiter, $value));
    }

    return $value;
}

function baseclass($namespace)
{
    $namespaceParts = explode('\\', $namespace);

    return implode('\\', array_slice($namespaceParts, 0, -1));
}
