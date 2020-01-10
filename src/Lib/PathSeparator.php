<?php

namespace BeeJeeMVC\Lib;

class PathSeparator
{
    /**
     * @param string $path
     * @param string $separator
     *
     * @return array
     */
    public static function separate(string $path, string $separator = '/'): array
    {
        return explode($separator, trim($path, $separator));
    }
}
