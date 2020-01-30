<?php

namespace Todo\Lib\Exceptions;

class CannotBeEmptyException extends \Exception
{
    public function __construct(string $fieldName)
    {
        parent::__construct($fieldName.' value cannot be empty.');
    }
}
