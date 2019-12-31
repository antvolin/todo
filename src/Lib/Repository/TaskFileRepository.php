<?php

namespace BeeJeeMVC\Lib\Repository;

use BeeJeeMVC\Lib\Ordering;
use BeeJeeMVC\Model\Task;
use FilesystemIterator;
use LogicException;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

class TaskFileRepository implements TaskRepositoryInterface
{
    /**
     * @var int
     */
    private $tasksPerPage;

    /**
     * @var string
     */
    private $taskFolderPath;

    /**
     * @param string $taskFolderPath
     * @param int $tasksPerPage
     */
    public function __construct(string $taskFolderPath, int $tasksPerPage)
    {
        $this->taskFolderPath = $taskFolderPath;
        $this->tasksPerPage = $tasksPerPage;
    }

    /**
     * @inheritdoc
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
     * @inheritdoc
     */
    public function getCountRows(): int
    {
        return iterator_count(new FilesystemIterator($this->taskFolderPath, FilesystemIterator::SKIP_DOTS));
    }

    /**
     * @inheritdoc
     */
    public function getList(int $page, ?string $orderBy = null, ?string $order = null): array
    {
        $tasks = [];

        foreach (glob($this->taskFolderPath.'*') as $file) {
            $tasks[basename($file)] = unserialize(file_get_contents($file), ['allowed_classes' => true]);
        }

        if ($orderBy && $order) {
            $method = 'get'.ucfirst($orderBy);

            if (Ordering::ASC === $order) {
                uasort($tasks, function (Task $a, Task $b) use ($method) {
                    return strcmp(strtolower($a->$method()), strtolower($b->$method()));
                });
            } else {
                uasort($tasks, function (Task $b, Task $a) use ($method) {
                    return strcmp(strtolower($a->$method()), strtolower($b->$method()));
                });
            }
        }

        return array_slice($tasks, ($page - 1) * $this->tasksPerPage, $this->tasksPerPage);
    }

    /**
     * @inheritdoc
     */
    public function save(Task $task): void
    {
        file_put_contents($this->taskFolderPath.$task->getId(), serialize($task));
    }
}
