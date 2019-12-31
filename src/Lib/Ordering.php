<?php

namespace BeeJeeMVC\Lib;

class Ordering
{
    public const ASC = 'ASC';
    public const DESC = 'DESC';
    public const ALLOWED_ORDERS = [
        self::ASC,
        self::DESC,
    ];
    public const ALLOWED_ORDER_FIELDS = [
        'user_name',
        'email',
        'text',
    ];

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
