<?php

namespace Todo\Lib\Service\Path;

class PathService implements PathServiceInterface
{
    /**
     * @inheritDoc
     */
    public static function getFirstPart(string $path, string $separator = '/'): string
    {
        return strtolower(self::getPathParts($path, $separator)[1]);
    }

    /**
     * @inheritDoc
     */
    public static function getPathParts(string $path, string $separator = '/'): array
    {
        return explode($separator, trim($path, $separator));
    }

    /**
     * @inheritDoc
     */
    public static function getSrcPathByLevel(int $level = 0): string
    {
        $path = '';

        for ($i = $level; $i > 0; --$i) {
            $path .= '/..';
        }

        return dirname(__DIR__) . $path;
    }

    /**
     * @inheritDoc
     */
    public static function getPathToPdoDsn(string $pdoType, string $dbFolderName, string $entityName): string
    {
        return $pdoType.':'.dirname(__DIR__).'/../../..'.$dbFolderName.$entityName;
    }

    /**
     * @inheritDoc
     */
    public static function getPathToTemplates(): string
    {
        return dirname(__DIR__) . '/../../../templates';
    }
}
