<?php

namespace BeeJeeMVC\Lib\Factory;

use BeeJeeMVC\Lib\Paginator\PaginatorInterface;

abstract class PaginatorFactory
{
    /**
     * @param array $rows
     * @param int $countRows
     * @param int $page
     *
     * @return PaginatorInterface
     */
    abstract public function create(array $rows, int $countRows, int $page): PaginatorInterface;
}
