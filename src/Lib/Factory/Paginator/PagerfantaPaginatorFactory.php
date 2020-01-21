<?php

namespace Todo\Lib\Factory\Paginator;

use Todo\Lib\Paginator\PagerfantaPaginator;
use Todo\Lib\Paginator\PaginatorAdapterInterface;
use Todo\Lib\Paginator\PaginatorInterface;

class PagerfantaPaginatorFactory implements PaginatorFactoryInterface
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
