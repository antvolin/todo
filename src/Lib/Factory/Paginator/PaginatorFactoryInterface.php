<?php

namespace Todo\Lib\Factory\Paginator;

use Todo\Lib\Paginator\PaginatorAdapterInterface;
use Todo\Lib\Paginator\PaginatorInterface;

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
