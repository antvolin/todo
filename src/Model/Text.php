<?php

namespace BeeJeeMVC\Model;

use BeeJeeMVC\Lib\Exceptions\CannotBeEmptyException;

class Text
{
    /**
     * @var string
     */
    private $value;

    /**
     * @param string $value
     *
     * @throws CannotBeEmptyException
     */
    public function __construct(string $value)
    {
        if (!$value) {
            throw new CannotBeEmptyException('Text');
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
