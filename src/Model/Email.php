<?php

namespace BeeJeeMVC\Model;

use InvalidArgumentException;

class Email
{
    private const EMAIL_REG_EXP = "/(?:[a-z0-9!#$%&'*+\/\=\?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+\/\=\?^_`{|}~-]+)*|\"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*\")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:(2(5[0-5]|[0-4][0-9])|1[0-9][0-9]|[1-9]?[0-9]))\.){3}(?:(2(5[0-5]|[0-4][0-9])|1[0-9][0-9]|[1-9]?[0-9])|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])/im";

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
            throw new InvalidArgumentException('Email value cannot be empty.');
        }

        if (!preg_match(self::EMAIL_REG_EXP, $value)) {
            throw new InvalidArgumentException('Email value must be a valid email address.');
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
