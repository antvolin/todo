<?php

namespace Todo\Model;

use Todo\Lib\Exceptions\CannotBeEmptyException;

class Text
{
    private string $value;

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
