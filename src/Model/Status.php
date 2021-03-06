<?php

namespace Todo\Model;

use Todo\Lib\Exceptions\ForbiddenStatusException;

class Status
{
    public const EDITED = 'edited';
    public const DONE = 'done';
    public const ALLOWED_STATUSES = [
        self::EDITED,
        self::DONE,
    ];
    private ?string $value = null;

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

    public function __toString(): string
    {
        return (string) $this->value;
    }
}
