<?php

namespace BeeJeeMVC\Model;

use BeeJeeMVC\Lib\AllowedStatuses;
use InvalidArgumentException;

class Status
{
    /**
     * @var string
     */
    private $value;

    /**
     * @param string $value
     */
    public function __construct(string $value)
    {
        if (!in_array($value, (new AllowedStatuses())->getAllowedStatuses(), true)) {
            $msg = 'Status value not allowed.';

            throw new InvalidArgumentException($msg);
        }

        $this->value = $value;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getValue();
    }

    /**
     * @return string
     */
    private function getValue(): string
    {
        return $this->value;
    }
}
