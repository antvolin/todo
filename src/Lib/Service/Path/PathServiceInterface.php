<?php

namespace Todo\Lib\Service\Path;

interface PathServiceInterface
{
    /**
     * @param string $path
     * @param string $separator
     *
     * @return string
     */
    public static function getFirstPathPart(string $path, string $separator = '/'): string;

    /**
     * @param string $path
     * @param string $separator
     *
     * @return array
     */
    public static function separatePath(string $path, string $separator = '/'): array;

    /**
     * @param string $entity
     * @param int $level
     *
     * @return string
     */
    public static function getPathToEntityStorage(string $entity, int $level = 0): string;

    /**
     * @param string $pdoType
     * @param string $dbFolderName
     * @param string $entityName
     *
     * @return string
     */
    public static function getPathToPdoDsn(string $pdoType, string $dbFolderName, string $entityName): string;

    /**
     * @return string
     */
    public static function getPathToTemplates(): string;
}
