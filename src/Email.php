<?php

namespace BeeJeeMVC;

use InvalidArgumentException;

class Email implements TaskFieldInterface
{
    public const FIELD_NAME = 'Email';

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

        $emailRegExp = (new EmailRegExp())->getRegExp();

        if (!preg_match($emailRegExp, $value)) {
            $msg = sprintf('%s value must be a valid email address.', self::FIELD_NAME);

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
