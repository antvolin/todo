<?php

namespace BeeJeeMVC\Lib\Exceptions;

class NotUniqueFieldsException extends \Exception
{
    protected $message = 'Entity with identical field values already exists!';
}
