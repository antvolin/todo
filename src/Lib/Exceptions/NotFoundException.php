<?php

namespace Todo\Lib\Exceptions;

class NotFoundException extends \Exception
{
    protected $message = 'Entity not found!';
}
