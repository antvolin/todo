<?php

namespace BeeJeeMVC;

use InvalidArgumentException;

class Text implements TaskFieldInterface
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
            $msg = sprintf('%s value cannot be empty.', self::FIELD_NAME);

            throw new InvalidArgumentException($msg);
        }

        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
