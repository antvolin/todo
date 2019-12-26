<?php

namespace BeeJeeMVC\Lib;

use BeeJeeMVC\Model\Task;

interface EntityRepositoryInterface
{
    /**
     * @param string $id
     *
     * @return Task
     */
    public function getById(string $id): Task;

    /**
     * @param string|null $sortBy
     * @param string|null $orderBy
     *
     * @return array
     */
    public function getList(?string $sortBy = null, ?string $orderBy = null): array;

    /**
     * @param Task $task
     */
    public function save(Task $task): void;
}
