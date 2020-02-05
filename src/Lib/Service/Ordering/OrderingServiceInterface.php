<?php

namespace Todo\Lib\Service\Ordering;

interface OrderingServiceInterface
{
    public static function getOrderBy(?string $orderBy): string;

    public static function getOrder(?string $order): string;

    public static function getNextOrder(?string $order): string;
}
