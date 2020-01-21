<?php

namespace Todo\Lib\Service;

class OrderingService
{
    public const ASC = 'ASC';
    public const DESC = 'DESC';
    public const ALLOWED_ORDER_FIELDS = [
        self::ASC,
        self::DESC,
    ];
    public const ALLOWED_ORDER_BY_FIELDS = [
        'user_name',
        'email',
        'text',
        'status',
    ];

    /**
     * @param string|null $orderBy
     *
     * @return string
     */
    public static function getOrderBy(?string $orderBy): string
    {
        $key = array_search($orderBy, self::ALLOWED_ORDER_BY_FIELDS, true);

        return self::ALLOWED_ORDER_BY_FIELDS[$key];
    }

    /**
     * @param string|null $order
     *
     * @return string
     */
    public static function getOrder(?string $order): string
    {
        return $order === self::DESC ? self::DESC : self::ASC;
    }

    /**
     * @param string|null $order
     *
     * @return string
     */
    public static function getNextOrder(?string $order): string
    {
        return !$order || self::ASC === $order ? self::DESC : self::ASC;
    }
}
