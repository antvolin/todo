<?php

namespace Todo\Lib\Service\Paginator;

use Pagerfanta\Adapter\AdapterInterface;
use Traversable;

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

    /**
     * @return int
     */
    public function getNbResults(): int;

    /**
     * @param int $offset
     * @param int $length
     *
     * @return array|Traversable
     */
    public function getSlice($offset, $length);
}
