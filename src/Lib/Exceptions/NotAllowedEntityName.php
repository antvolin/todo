<?php

namespace Todo\Lib\Exceptions;

class NotAllowedEntityName extends \Exception
{
    protected $message = 'Entity name is not allowed!';
}
