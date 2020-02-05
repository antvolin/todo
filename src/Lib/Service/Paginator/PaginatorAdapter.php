<?php

namespace Todo\Lib\Service\Paginator;

class PaginatorAdapter implements PaginatorAdapterInterface
{
    private array $rows = [];
    private int $countRows;

    public function setCountRows(int $countRows): void
    {
        $this->countRows = $countRows;
    }

    public function setData(array $rows): void
    {
        $this->rows = $rows;
    }

    public function getNbResults(): int
    {
        return $this->countRows;
    }

    public function getSlice($offset, $length)
    {
        return array_slice($this->rows, 0, $length);
    }
}
