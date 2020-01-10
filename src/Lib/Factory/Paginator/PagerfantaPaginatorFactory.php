<?php

namespace BeeJeeMVC\Lib\Factory\Paginator;

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
     * @var int
     */
    private $entityPerPage;

    /**
     * @param PaginatorAdapterInterface $adapter
     * @param int $entityPerPage
     */
    public function __construct(PaginatorAdapterInterface $adapter, int $entityPerPage)
    {
        $this->adapter = $adapter;
        $this->entityPerPage = $entityPerPage;
    }

    /**
     * @inheritdoc
     */
    public function create(array $rows, int $countRows, int $page): PaginatorInterface
    {
        $this->adapter->setData($rows);
        $this->adapter->setCountRows($countRows);

        return new PagerfantaPaginator($this->adapter, $page, $this->entityPerPage);
    }
}
