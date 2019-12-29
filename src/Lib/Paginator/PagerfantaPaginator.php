<?php

namespace BeeJeeMVC\Lib\Paginator;

use Pagerfanta\Pagerfanta;
use Pagerfanta\View\DefaultView;

class PagerfantaPaginator
{
    /**
     * @var Pagerfanta
     */
    private $paginator;

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
    public function create(int $page): void
    {
        if (!$this->paginator) {
            $pager = new Pagerfanta($this->adapter);
            $pager->setMaxPerPage($_ENV['TASKS_PER_PAGE']);
            $pager->setCurrentPage($page);

            $this->paginator = $pager;
        }
    }

    /**
     * @inheritdoc
     */
    public function getCurrentPageResults(): array
    {
        return $this->paginator->getCurrentPageResults();
    }

    /**
     * @inheritdoc
     */
    public function getHtml(?string $sortBy, ?string $orderBy): string
    {
        $routeGenerator = function (int $page) use ($sortBy, $orderBy) {
            return sprintf('/task/list?page=%s&sortBy=%s&orderBy=%s', $page, $sortBy, $orderBy);
        };

        return (new DefaultView())->render($this->paginator, $routeGenerator);
    }
}
