<?php

namespace Todo\Lib\Paginator;

interface PaginatorInterface
{
    /**
     * @param PaginatorAdapterInterface $adapter
     * @param int $currentPage
     * @param int $entityPerPage
     */
    public function __construct(PaginatorAdapterInterface $adapter, int $currentPage, int $entityPerPage);

    /**
     * @return array
     */
    public function getCurrentPageResults(): array;

    /**
     * @param string|null $orderBy
     * @param string|null $order
     * @return string
     */
    public function getHtml(?string $orderBy, ?string $order): string;
}
