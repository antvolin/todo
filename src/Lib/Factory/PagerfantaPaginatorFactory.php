<?php

namespace BeeJeeMVC\Lib\Factory;

use BeeJeeMVC\Lib\Paginator\PagerfantaPaginator;
use BeeJeeMVC\Lib\Paginator\PaginatorAdapterInterface;
use BeeJeeMVC\Lib\Paginator\PaginatorInterface;

class PagerfantaPaginatorFactory extends PaginatorFactory
{
    /**
     * @var PaginatorAdapterInterface
     */
    private $adapter;

    /**
     * @param PaginatorAdapterInterface $adapter
     */
    public function __construct(PaginatorAdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @inheritdoc
     */
    public function create(array $rows, int $countRows, int $page): PaginatorInterface
    {
        $this->adapter->setData($rows);
        $this->adapter->setCountRows($countRows);

        return new PagerfantaPaginator($this->adapter, $page);
    }
}
