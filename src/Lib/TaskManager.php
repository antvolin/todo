<?php

namespace BeeJeeMVC\Lib;

use BeeJeeMVC\Model\Email;
use BeeJeeMVC\Model\Task;
use BeeJeeMVC\Model\Text;
use BeeJeeMVC\Model\UserName;

class TaskManager
{
    /**
     * @var EntityRepositoryInterface
     */
    private $repository;

    /**
     * @param EntityRepositoryInterface $repository
     */
    public function __construct(EntityRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param string|null $sortBy
     * @param string|null $orderBy
     *
     * @return array|\Traversable
     */
    public function getList(?string $sortBy, ?string $orderBy)
    {
        return $this->repository->getList($sortBy, $orderBy);
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
