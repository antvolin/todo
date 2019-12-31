<?php

namespace BeeJeeMVC\Lib\Repository;

use BeeJeeMVC\Lib\Exceptions\NotUniqueTaskFieldsException;
use BeeJeeMVC\Lib\Exceptions\TaskNotFoundException;
use BeeJeeMVC\Model\Task;

interface TaskRepositoryInterface
{
    /**
     * @param int $taskId
     *
     * @return Task
     *
     * @throws TaskNotFoundException
     */
    public function getById(int $taskId): Task;

    /**
     * @return int
     */
    public function getCountRows(): int;

    /**
     * @param int $page
     * @param string|null $orderBy
     * @param string|null $order
     *
     * @return array
     */
    public function getList(int $page, ?string $orderBy = null, ?string $order = null): array;

    /**
     * @param Task $task
     * @param int|null $taskId
     *
     * @throws NotUniqueTaskFieldsException
     */
    public function save(Task $task, ?int $taskId = null): void;
}
