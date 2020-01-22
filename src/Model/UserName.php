<?php

namespace Todo\Model;

use Todo\Lib\Exceptions\CannotBeEmptyException;

class UserName
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
            throw new CannotBeEmptyException('User');
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

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
