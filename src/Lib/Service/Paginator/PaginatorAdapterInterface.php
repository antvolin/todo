<?php

namespace Todo\Lib\Service\Paginator;

use Pagerfanta\Adapter\AdapterInterface;
use Traversable;

interface PaginatorAdapterInterface extends AdapterInterface
{
    public function setData(array $rows): void;

    public function setCountRows(int $countRows): void;

    public function getNbResults(): int;

    /**
     * @param int $offset
     * @param int $length
     *
     * @return array|Traversable
     */
    public function getSlice($offset, $length);
}
