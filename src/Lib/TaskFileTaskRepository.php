<?php

namespace BeeJeeMVC\Lib;

use BeeJeeMVC\Model\Task;
use LogicException;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

class TaskFileTaskRepository implements TaskRepositoryInterface
{
    /**
     * @var string
     */
    private $taskFolderPath;

    public function __construct()
    {
        $env = new Dotenv();
        $env->load(dirname(__DIR__).'/../.env');
        $this->taskFolderPath = dirname(__DIR__).$_ENV['TASK_FOLDER_NAME'];
    }

    /**
     * @param string $hash
     *
     * @return Task
     */
    public function getById(string $hash): Task
    {
        $file = file_get_contents($this->taskFolderPath.$hash);

        if (false === $file) {
            throw new FileNotFoundException('Invalid task id!');
        }

        $task = unserialize($file, ['allowed_classes' => true]);

        if (!$task) {
            throw new LogicException('Failed to produce unserialize task!');
        }

        return $task;
    }

    /**
     * @param string|null $sortBy
     * @param string|null $orderBy
     *
     * @return array
     */
    public function getList(?string $sortBy = null, ?string $orderBy = null): array
    {
        $files = glob($this->taskFolderPath.'*');
        $tasks = [];
        foreach ($files as $file) {
            $tasks[basename($file)] = unserialize(file_get_contents($file), ['allowed_classes' => true]);
        }

        if ($sortBy && $orderBy) {
            $method = 'get'.ucfirst($sortBy);

            if (Sorting::ASC === $orderBy) {
                uasort($tasks, function (Task $a, Task $b) use ($method) {
                    return strcmp($a->$method(), $b->$method());
                });
            } else {
                uasort($tasks, function (Task $b, Task $a) use ($method) {
                    return strcmp($a->$method(), $b->$method());
                });
            }
        }

        return $tasks;
    }

    /**
     * @param Task $task
     */
    public function save(Task $task): void
    {
        file_put_contents($this->taskFolderPath.$task->getId(), serialize($task));
    }
}
