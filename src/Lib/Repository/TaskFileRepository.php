<?php

namespace BeeJeeMVC\Lib\Repository;

use BeeJeeMVC\Lib\Sorting;
use BeeJeeMVC\Model\Task;
use LogicException;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

class TaskFileRepository implements TaskRepositoryInterface
{
    /**
     * @var string
     */
    private $taskFolderPath;

    /**
     * @param string $taskFolderPath
     */
    public function __construct(string $taskFolderPath)
    {
        $this->taskFolderPath = $taskFolderPath;
    }

    /**
     * @param string $id
     *
     * @return Task
     */
    public function getById(string $id): Task
    {
        $file = file_get_contents($this->taskFolderPath.$id);

        if (false === $file) {
            throw new FileNotFoundException('Invalid task id!');
        }

        $task = unserialize($file, ['allowed_classes' => true]);

        if (!$task) {
            throw new LogicException('Failed to produce un serialize task!');
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
        $tasks = [];

        foreach (glob($this->taskFolderPath.'*') as $file) {
            $tasks[basename($file)] = unserialize(file_get_contents($file), ['allowed_classes' => true]);
        }

        if ($sortBy && $orderBy) {
            $method = 'get'.ucfirst($sortBy);

            if (Sorting::ASC === $orderBy) {
                uasort($tasks, function (Task $a, Task $b) use ($method) {
                    return strcmp(strtolower($a->$method()), strtolower($b->$method()));
                });
            } else {
                uasort($tasks, function (Task $b, Task $a) use ($method) {
                    return strcmp(strtolower($a->$method()), strtolower($b->$method()));
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
