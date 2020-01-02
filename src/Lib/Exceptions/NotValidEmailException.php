<?php

namespace BeeJeeMVC\Lib\Exceptions;

class NotValidEmailException extends \Exception
{
    protected $message = 'Email value must be a valid email address.';
}
