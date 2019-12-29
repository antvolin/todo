<?php

namespace BeeJeeMVC\Lib\Paginator;

use Pagerfanta\Adapter\AdapterInterface;

interface PaginatorAdapterInterface extends AdapterInterface
{
    /**
     * @param array $rows
     */
    public function setRows(array $rows): void;
}
