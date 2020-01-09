<?php

namespace BeeJeeMVC\Lib\Factory\Repository;

use BeeJeeMVC\Lib\Repository\TaskFileRepository;
use BeeJeeMVC\Lib\Repository\TaskRepositoryInterface;

class TaskFileRepositoryFactory extends TaskRepositoryFactory
{
    /**
     * @return TaskRepositoryInterface
     */
    public function create(): TaskRepositoryInterface
    {
        return new TaskFileRepository(dirname(__DIR__) . '/src/' .$_ENV['TASK_FOLDER_NAME'], $_ENV['TASKS_PER_PAGE']);
    }
}
