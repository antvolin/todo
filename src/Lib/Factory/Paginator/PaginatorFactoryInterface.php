<?php

namespace Todo\Lib\Factory\Paginator;

use Todo\Lib\Service\Paginator\PaginatorAdapterInterface;
use Todo\Lib\Service\Paginator\PaginatorServiceInterface;

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
     * @return PaginatorServiceInterface
     */
    public function create(array $rows, int $countRows, int $page): PaginatorServiceInterface;
}
