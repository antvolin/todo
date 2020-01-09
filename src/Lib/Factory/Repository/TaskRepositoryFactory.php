<?php

namespace BeeJeeMVC\Lib\Factory\Repository;

use BeeJeeMVC\Lib\Repository\TaskRepositoryInterface;

abstract class TaskRepositoryFactory
{
    /**
     * @return TaskRepositoryInterface
     */
    abstract public function create(): TaskRepositoryInterface;
}
