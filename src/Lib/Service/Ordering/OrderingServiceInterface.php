<?php

namespace Todo\Lib\Service\Ordering;

interface OrderingServiceInterface
{
    public function orderCollection(array $collection, string $orderBy, string $order): array;

    public function getNextOrder(?string $order): string;
}
