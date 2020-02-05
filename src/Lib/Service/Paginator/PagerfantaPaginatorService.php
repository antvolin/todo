<?php

namespace Todo\Lib\Service\Paginator;

use Pagerfanta\Pagerfanta;
use Pagerfanta\View\DefaultView;

class PagerfantaPaginatorService implements PaginatorServiceInterface
{
    private Pagerfanta $paginator;

    public function __construct(PaginatorAdapterInterface $adapter, int $page, int $entityPerPage)
    {
        $this->paginator = new Pagerfanta($adapter);
        $this->paginator->setMaxPerPage($entityPerPage);
        $this->paginator->setCurrentPage($page);
    }

    public function getCurrentPageResults(): array
    {
        return $this->paginator->getCurrentPageResults();
    }

    public function getHtml(?string $orderBy, ?string $order): string
    {
        $routeGenerator = static function (int $page) use ($orderBy, $order) {
            return sprintf('/entity/list?page=%s&orderBy=%s&order=%s', $page, $orderBy, $order);
        };

        return (new DefaultView())->render($this->paginator, $routeGenerator);
    }
}
