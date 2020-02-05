<?php

namespace Todo\Lib\Exceptions;

class CannotCreateDirectoryException extends \Exception
{
    public function __construct(string $msg)
    {
        parent::__construct($msg);
    }
}
