<?php

namespace Todo\Lib\Service\Ordering;

use Todo\Model\EntityInterface;

class EntityOrderingService implements OrderingServiceInterface
{
    public const ASC = 'ASC';
    public const DESC = 'DESC';
    public const ALLOWED_ORDER_BY_FIELDS = [
        'user_name',
        'email',
        'text',
        'status',
    ];

    public function orderCollection(array $collection, string $orderBy, string $order): array
    {
        $methodName = explode('_', $orderBy);
        array_map(static function($path) {
            ucfirst($path);
        }, $methodName);
        $method = 'get'.implode('', $methodName);

        if (self::ASC === $order) {
            uasort($collection, static function (EntityInterface $a, EntityInterface $b) use ($method) {
                return strcmp(strtolower($a->$method()), strtolower($b->$method()));
            });
        } else {
            uasort($collection, static function (EntityInterface $b, EntityInterface $a) use ($method) {
                return strcmp(strtolower($a->$method()), strtolower($b->$method()));
            });
        }

        return $collection;
    }

    public function getNextOrder(?string $order): string
    {
        return !$order || self::ASC === $order ? self::DESC : self::ASC;
    }
}
