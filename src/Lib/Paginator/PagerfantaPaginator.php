<?php

namespace Todo\Lib\Paginator;

use Pagerfanta\Pagerfanta;
use Pagerfanta\View\DefaultView;

class PagerfantaPaginator implements PaginatorInterface
{
    /**
     * @var Pagerfanta
     */
    private $paginator;

    /**
     * @inheritdoc
     */
    public function __construct(PaginatorAdapterInterface $adapter, int $page, int $entityPerPage)
    {
        if (!$this->paginator) {
            $pager = new Pagerfanta($adapter);
            $pager->setMaxPerPage($entityPerPage);
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
    public function getHtml(?string $orderBy, ?string $order): string
    {
        $routeGenerator = function (int $page) use ($orderBy, $order) {
            return sprintf('/entity/list?page=%s&orderBy=%s&order=%s', $page, $orderBy, $order);
        };

        return (new DefaultView())->render($this->paginator, $routeGenerator);
    }
}
