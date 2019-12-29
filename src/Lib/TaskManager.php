<?php

namespace BeeJeeMVC\Lib;

use BeeJeeMVC\Lib\Repository\TaskRepositoryInterface;
use BeeJeeMVC\Model\Email;
use BeeJeeMVC\Model\Task;
use BeeJeeMVC\Model\Text;
use BeeJeeMVC\Model\UserName;

class TaskManager
{
    /**
     * @var TaskRepositoryInterface
     */
    private $repository;

    /**
     * @param TaskRepositoryInterface $repository
     */
    public function __construct(TaskRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param string $id
     *
     * @return Task
     */
    public function getById(string $id): Task
    {
        return $this->repository->getById($id);
    }

    /**
     * @param int $page
     * @param string|null $sortBy
     * @param string|null $orderBy
     *
     * @return array
     */
    public function getList(int $page, ?string $sortBy, ?string $orderBy): array
    {
        return $this->repository->getList($page, $sortBy, $orderBy);
    }

    /**
     * @return int
     */
    public function getCountRows(): int
    {
        return $this->repository->getCountRows();
    }

    /**
     * @param string $userName
     * @param string $email
     * @param string $text
     */
    public function save(string $userName, string $email, string $text): void
    {
        $this->repository->save(new Task(new UserName($userName), new Email($email), new Text($text)));
    }

    /**
     * @param string $taskId
     * @param string $text
     */
    public function edit(string $taskId, string $text): void
    {
        $task = $this->repository->getById($taskId);
        $task->edit($text);
        $this->repository->save($task);
    }

    /**
     * @param string $taskId
     */
    public function done(string $taskId): void
    {
        $task = $this->repository->getById($taskId);
        $task->done();
        $this->repository->save($task);
    }
}
