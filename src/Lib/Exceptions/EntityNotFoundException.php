<?php

namespace Todo\Lib\Exceptions;

class EntityNotFoundException extends \Exception
{
    protected $message = 'Entity not found!';
}
