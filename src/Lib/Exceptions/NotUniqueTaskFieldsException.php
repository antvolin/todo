<?php

namespace BeeJeeMVC\Lib\Exceptions;

class NotUniqueTaskFieldsException extends \Exception
{
    protected $message = 'Task with identical field values already exists!';
}
