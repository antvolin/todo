<?php

namespace BeeJeeMVC\Lib\Repository;

use BeeJeeMVC\Model\Task;

interface TaskRepositoryInterface
{
    /**
     * @param string $id
     *
     * @return Task
     */
    public function getById(string $id): Task;

    /**
     * @return int
     */
    public function getCountRows(): int;

    /**
     * @param int $page
     * @param string|null $sortBy
     * @param string|null $orderBy
     *
     * @return array
     */
    public function getList(int $page, ?string $sortBy = null, ?string $orderBy = null): array;

    /**
     * @param Task $task
     */
    public function save(Task $task): void;
}
