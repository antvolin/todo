<?php

namespace BeeJeeMVC\Lib;

class Sorting
{
    public const ASC = 'ASC';
    public const DESC = 'DESC';

    /**
     * @param string|null $orderBy
     *
     * @return string
     */
    public function getNextOrderBy(?string $orderBy): string
    {
        return !$orderBy || self::ASC === $orderBy ? self::DESC : self::ASC;
    }
}
