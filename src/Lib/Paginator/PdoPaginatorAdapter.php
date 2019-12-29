<?php

namespace BeeJeeMVC\Lib\Paginator;

class PdoPaginatorAdapter implements PaginatorAdapterInterface
{
    /**
     * @var array
     */
    private $rows = [];

    /**
     * @param array $rows
     */
    public function setRows(array $rows): void
    {
        $this->rows = $rows;
    }

    /**
     * @inheritdoc
     */
    public function getNbResults()
    {
        return count($this->rows);
    }

    /**
     * @inheritdoc
     */
    public function getSlice($offset, $length)
    {
        array_slice($this->rows, $offset, $length);
    }
}
