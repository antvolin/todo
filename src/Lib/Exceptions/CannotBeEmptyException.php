<?php

namespace Todo\Lib\Exceptions;

class CannotBeEmptyException extends \Exception
{
    protected $message = ' value cannot be empty.';

    public function __construct(string $fieldName)
    {
        parent::__construct($fieldName.$this->message);
    }
}
