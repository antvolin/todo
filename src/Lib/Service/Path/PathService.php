<?php

namespace Todo\Lib\Service\Path;

class PathService implements PathServiceInterface
{
    private const DIRECTORY_SEPARATOR = '/';

    /**
     * @inheritDoc
     */
    public static function getFirstPart(string $path, string $separator = self::DIRECTORY_SEPARATOR): string
    {
        return strtolower(self::getPathParts($path, $separator)[1]);
    }

    /**
     * @inheritDoc
     */
    public static function getPathParts(string $path, string $separator = self::DIRECTORY_SEPARATOR): array
    {
        return explode($separator, trim($path, $separator));
    }

    /**
     * @inheritDoc
     */
    public static function getPathToEntityStorage(string $entityName, int $level = 0): string
    {
        return sprintf(
            '%s%s%s/',
            dirname(__DIR__),
            self::generatePathToBack($level),
            $entityName
        );
    }

    /**
     * @inheritDoc
     */
    public static function getPathToPdoDsn(string $pdoType, string $dbFolderName, string $entityName): string
    {
        return sprintf(
            '%s:%s%s%s/%s',
            $pdoType,
            dirname(__DIR__),
            self::generatePathToBack(3),
            $dbFolderName,
            $entityName
        );
    }

    /**
     * @inheritDoc
     */
    public static function getPathToTemplates(): string
    {
        return sprintf(
            '%s%s%s',
            dirname(__DIR__),
            self::generatePathToBack(3),
            'templates'
        );
    }

    /**
     * @param int $level
     *
     * @return string
     */
    private static function generatePathToBack(int $level): string
    {
        $path = self::DIRECTORY_SEPARATOR;

        for ($i = $level; $i > 0; --$i) {
            $path .= '..'.self::DIRECTORY_SEPARATOR;
        }

        return $path;
    }
}
