<?php

namespace Todo\Lib\Exceptions;

class PdoConnectionException extends \Exception
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}
