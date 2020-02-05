<?php

namespace Todo\Lib\Factory\Paginator;

use Todo\Lib\Service\Paginator\PaginatorAdapterInterface;
use Todo\Lib\Service\Paginator\PaginatorServiceInterface;

interface PaginatorFactoryInterface
{
    public function __construct(PaginatorAdapterInterface $adapter, int $entityPerPage);

    public function create(array $rows, int $countRows, int $page): PaginatorServiceInterface;
}
