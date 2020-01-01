<?php

namespace BeeJeeMVC\Lib\Exceptions;

class CannotEditTaskException extends \Exception
{
    protected $message = 'Cannot edit the completed task!';
}
