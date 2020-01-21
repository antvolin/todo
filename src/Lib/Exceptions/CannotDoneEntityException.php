<?php

namespace Todo\Lib\Exceptions;

class CannotDoneEntityException extends \Exception
{
    protected $message = 'Cannot done the completed entity!';
}
