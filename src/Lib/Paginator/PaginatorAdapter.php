<?php

namespace Todo\Lib\Paginator;

class PaginatorAdapter implements PaginatorAdapterInterface
{
    /**
     * @var array
     */
    private $rows = [];

    /**
     * @var int
     */
    private $countRows;

    /**
     * @inheritdoc
     */
    public function setCountRows(int $countRows): void
    {
        $this->countRows = $countRows;
    }

    /**
     * @inheritdoc
     */
    public function setData(array $rows): void
    {
        $this->rows = $rows;
    }

    /**
     * @inheritdoc
     */
    public function getNbResults(): int
    {
        return $this->countRows;
    }

    /**
     * @inheritdoc
     */
    public function getSlice($offset, $length)
    {
        return array_slice($this->rows, 0, $length);
    }
}
