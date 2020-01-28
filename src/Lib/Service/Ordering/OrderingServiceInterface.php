<?php

namespace Todo\Lib\Service\Ordering;

interface OrderingServiceInterface
{
    /**
     * @param string|null $orderBy
     *
     * @return string
     */
    public static function getOrderBy(?string $orderBy): string;

    /**
     * @param string|null $order
     *
     * @return string
     */
    public static function getOrder(?string $order): string;

    /**
     * @param string|null $order
     *
     * @return string
     */
    public static function getNextOrder(?string $order): string;
}
