<?php

namespace BeeJeeMVC\Model;

use BeeJeeMVC\Lib\EmailRegExp;
use InvalidArgumentException;

class Email
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
            $msg = 'Email value cannot be empty.';

            throw new InvalidArgumentException($msg);
        }

        $emailRegExp = (new EmailRegExp())->getRegExp();

        if (!preg_match($emailRegExp, $value)) {
            $msg = 'Email value must be a valid email address.';

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
