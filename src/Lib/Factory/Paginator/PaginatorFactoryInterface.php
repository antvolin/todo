<?php

namespace BeeJeeMVC\Lib\Factory\Paginator;

use BeeJeeMVC\Lib\Paginator\PaginatorAdapterInterface;
use BeeJeeMVC\Lib\Paginator\PaginatorInterface;

interface PaginatorFactoryInterface
{
    /**
     * @param PaginatorAdapterInterface $adapter
     * @param int $entityPerPage
     */
    public function __construct(PaginatorAdapterInterface $adapter, int $entityPerPage);

    /**
     * @param array $rows
     * @param int $countRows
     * @param int $page
     *
     * @return PaginatorInterface
     */
    public function create(array $rows, int $countRows, int $page): PaginatorInterface;
}
