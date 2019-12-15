<?php

namespace BeeJeeMVC\Model;

use InvalidArgumentException;

class Text
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
        if (!$value) {
            throw new InvalidArgumentException('Text value cannot be empty.');
        }

        $this->value = $value;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->value;
    }
}
