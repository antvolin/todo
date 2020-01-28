<?php

namespace Todo\Lib\Service\Ordering;

class OrderingService implements OrderingServiceInterface
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
     * @inheritDoc
     */
    public static function getOrderBy(?string $orderBy): string
    {
        $key = array_search($orderBy, self::ALLOWED_ORDER_BY_FIELDS, true);

        return self::ALLOWED_ORDER_BY_FIELDS[$key];
    }

    /**
     * @pinheritDoc
     */
    public static function getOrder(?string $order): string
    {
        return $order === self::DESC ? self::DESC : self::ASC;
    }

    /**
     * @inheritDoc
     */
    public static function getNextOrder(?string $order): string
    {
        return !$order || self::ASC === $order ? self::DESC : self::ASC;
    }
}
