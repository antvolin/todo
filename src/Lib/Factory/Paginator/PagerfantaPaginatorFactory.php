<?php

namespace Todo\Lib\Factory\Paginator;

use Todo\Lib\Service\Paginator\PagerfantaPaginatorService;
use Todo\Lib\Service\Paginator\PaginatorAdapterInterface;
use Todo\Lib\Service\Paginator\PaginatorServiceInterface;

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
     * @inheritdoc
     */
    public function __construct(PaginatorAdapterInterface $adapter, int $entityPerPage)
    {
        $this->adapter = $adapter;
        $this->entityPerPage = $entityPerPage;
    }

    /**
     * @inheritdoc
     */
    public function create(array $rows, int $countRows, int $page): PaginatorServiceInterface
    {
        $this->adapter->setData($rows);
        $this->adapter->setCountRows($countRows);

        return new PagerfantaPaginatorService($this->adapter, $page, $this->entityPerPage);
    }
}
