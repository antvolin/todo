<?php

namespace BeeJeeMVC\Lib\Paginator;

interface PaginatorInterface
{
    /**
     * @param PaginatorAdapterInterface $adapter
     * @param int $current
     */
    public function __construct(PaginatorAdapterInterface $adapter, int $current);

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
