<?php

namespace BeeJeeMVC\Model;

use InvalidArgumentException;

class Status
{
    public const STATUS_EDITED = 'edited';
    public const STATUS_DONE = 'done';
    private const ALLOWED_STATUSES = [
        self::STATUS_EDITED,
        self::STATUS_DONE,
    ];

    /**
     * @var string
     */
    private $value;

    /**
     * @param string $value
     */
    public function __construct(string $value)
    {
        if (!in_array($value, self::ALLOWED_STATUSES, true)) {
            throw new InvalidArgumentException('This status cannot be used!');
        }

        $this->value = $value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->value;
    }
}
