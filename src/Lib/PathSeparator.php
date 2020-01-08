<?php

namespace BeeJeeMVC\Lib;

class PathSeparator
{
    /**
     * @param string $path
     *
     * @return array
     */
    public static function separate(string $path): array
    {
        return explode('/', trim($path, '/'));
    }
}
