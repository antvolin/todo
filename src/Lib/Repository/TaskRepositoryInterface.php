<?php

namespace BeeJeeMVC\Lib\Repository;

use BeeJeeMVC\Lib\Exceptions\NotUniqueTaskFieldsException;
use BeeJeeMVC\Lib\Exceptions\TaskNotFoundException;
use BeeJeeMVC\Model\Task;

interface TaskRepositoryInterface
{
    /**
     * @param string $id
     *
     * @return Task
     *
     * @throws TaskNotFoundException
     */
    public function getById(string $id): Task;

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
     *
     * @throws NotUniqueTaskFieldsException
     */
    public function save(Task $task): void;
}
