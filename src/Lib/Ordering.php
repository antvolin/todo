<?php

namespace BeeJeeMVC\Lib;

class Ordering
{
    private const ASC = 'ASC';
    private const DESC = 'DESC';
    private const ALLOWED_ORDER_FIELDS = [
        'user_name',
        'email',
        'text',
    ];

    /**
     * @param string $orderBy
     *
     * @return string
     */
    public static function getOrderBy(string $orderBy): string
    {
        $key = array_search($orderBy, self::ALLOWED_ORDER_FIELDS, true);

        return self::ALLOWED_ORDER_FIELDS[$key];
    }

    /**
     * @param string $order
     *
     * @return string
     */
    public static function getOrder(string $order): string
    {
        return $order === self::DESC ? self::DESC : self::ASC;
    }

    /**
     * @param string|null $order
     *
     * @return string
     */
    public function getNextOrder(?string $order): string
    {
        return !$order || self::ASC === $order ? self::DESC : self::ASC;
    }
}
