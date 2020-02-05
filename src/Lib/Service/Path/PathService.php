<?php

namespace Todo\Lib\Service\Path;

class PathService implements PathServiceInterface
{
    private const DIRECTORY_SEPARATOR = '/';

    public static function getFirstPathPart(string $path, string $separator = self::DIRECTORY_SEPARATOR): string
    {
        return strtolower(self::separatePath($path, $separator)[1]);
    }

    public static function separatePath(string $path, string $separator = self::DIRECTORY_SEPARATOR): array
    {
        return explode($separator, trim($path, $separator));
    }

    public static function getPathToEntityStorage(string $entityName, int $level = 0): string
    {
        return sprintf(
            '%s%s%s/',
            dirname(__DIR__),
            self::generatePathToBack($level),
            $entityName
        );
    }

    public static function getPathToTemplates(): string
    {
        return sprintf(
            '%s%s%s',
            dirname(__DIR__),
            self::generatePathToBack(3),
            'templates'
        );
    }

    private static function generatePathToBack(int $level): string
    {
        $path = self::DIRECTORY_SEPARATOR;

        for ($i = $level; $i > 0; --$i) {
            $path .= '..'.self::DIRECTORY_SEPARATOR;
        }

        return $path;
    }
}
