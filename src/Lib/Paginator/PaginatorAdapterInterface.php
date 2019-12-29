<?php

namespace BeeJeeMVC\Lib\Paginator;

use Pagerfanta\Adapter\AdapterInterface;

interface PaginatorAdapterInterface extends AdapterInterface
{
    /**
     * @param array $rows
     */
    public function setData(array $rows): void;

    /**
     * @param int $countRows
     */
    public function setCountRows(int $countRows): void;
}
