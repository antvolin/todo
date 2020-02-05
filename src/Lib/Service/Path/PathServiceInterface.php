<?php

namespace Todo\Lib\Service\Path;

interface PathServiceInterface
{
    public static function getFirstPathPart(string $path, string $separator = '/'): string;

    public static function separatePath(string $path, string $separator = '/'): array;

    public static function getPathToEntityStorage(string $entity, int $level = 0): string;

    public static function getPathToTemplates(): string;
}
