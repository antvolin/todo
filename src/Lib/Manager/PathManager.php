<?php

namespace BeeJeeMVC\Lib\Manager;

class PathManager
{
    /**
     * @param string $path
     * @param string $separator
     *
     * @return string
     */
    public static function getFirstPart(string $path, string $separator = '/'): string
    {
        return strtolower(self::getPathParts($path, $separator)[1]);
    }

    /**
     * @param string $path
     * @param string $separator
     *
     * @return array
     */
    public static function getPathParts(string $path, string $separator = '/'): array
    {
        return explode($separator, trim($path, $separator));
    }

    /**
     * @param int $level
     *
     * @return string
     */
    public static function getSrcPathByLevel(int $level = 0): string
    {
        $path = '';

        for ($i = $level; $i > 0; --$i) {
            $path .= '/..';
        }

        return dirname(__DIR__) . $path. '/src/';
    }

    /**
     * @param string $pdoType
     * @param string $dbFolderName
     * @param string $entityName
     *
     * @return string
     */
    public static function getPathToPdoDsn(string $pdoType, string $dbFolderName, string $entityName): string
    {
        return $pdoType.':'.dirname(__DIR__).'/../../'.$dbFolderName.$entityName;
    }

    /**
     * @return string
     */
    public static function getPathToTemplates(): string
    {
        return dirname(__DIR__) . '/../../templates';
    }
}
