<?php

namespace Todo\Lib\Exceptions;

class ForbiddenStatusException extends \Exception
{
    protected $message = 'This status is forbidden to use!';
}
