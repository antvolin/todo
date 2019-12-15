<?php

namespace BeeJeeMVC\Model;

use InvalidArgumentException;

class Text
{
    public const FIELD_NAME = 'Text';

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
            $msg = 'Text value cannot be empty.';

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
