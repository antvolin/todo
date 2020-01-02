<?php

namespace BeeJeeMVC\Model;

use BeeJeeMVC\Lib\Exceptions\ForbiddenStatusException;

class Status
{
    public const EDITED = 'edited';
    public const DONE = 'done';
    private const ALLOWED_STATUSES = [
        self::EDITED,
        self::DONE,
    ];

    /**
     * @var string
     */
    private $value;

    /**
     * @param string|null $value
     *
     * @throws ForbiddenStatusException
     */
    public function __construct(?string $value = null)
    {
        if ($value) {
            if (!in_array($value, self::ALLOWED_STATUSES, true)) {
                throw new ForbiddenStatusException();
            }

            $this->value = $value;
        }
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->value;
    }
}
