<?php

namespace Todo\Lib\Service\Paginator;

interface PaginatorServiceInterface
{
    public function __construct(PaginatorAdapterInterface $adapter, int $currentPage, int $entityPerPage);

    public function getCurrentPageResults(): array;

    public function getHtml(?string $orderBy, ?string $order): string;
}
