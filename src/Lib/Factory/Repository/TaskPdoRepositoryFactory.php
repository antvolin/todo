<?php

namespace BeeJeeMVC\Lib\Factory\Repository;

use BeeJeeMVC\Lib\Repository\TaskPdoRepository;
use BeeJeeMVC\Lib\Repository\TaskRepositoryInterface;

class TaskPdoRepositoryFactory extends TaskRepositoryFactory
{
    /**
     * @return TaskRepositoryInterface
     */
    public function create(): TaskRepositoryInterface
    {
        return new TaskPdoRepository($_ENV['TASKS_PER_PAGE']);
    }
}
