<?php

namespace BeeJeeMVC\Lib;

use BeeJeeMVC\Lib\Repository\TaskRepositoryInterface;
use BeeJeeMVC\Model\Email;
use BeeJeeMVC\Model\Status;
use BeeJeeMVC\Model\Task;
use BeeJeeMVC\Model\Text;
use BeeJeeMVC\Model\UserName;
use BeeJeeMVC\Model\Id;

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
     * @param int $id
     *
     * @return Task
     *
     * @throws Exceptions\TaskNotFoundException
     */
    public function getById(int $id): Task
    {
        return $this->repository->getById($id);
    }

    /**
     * @param int $page
     * @param string|null $orderBy
     * @param string|null $order
     *
     * @return array
     */
    public function getList(int $page, ?string $orderBy, ?string $order): array
    {
        return $this->repository->getList($page, $orderBy, $order);
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
     *
     * @throws Exceptions\NotUniqueTaskFieldsException
     */
    public function save(string $userName, string $email, string $text): void
    {
        $this->repository->save(new Task(new Id(), new UserName($userName), new Email($email), new Text($text), new Status()));
    }

    /**
     * @param int $taskId
     * @param string $text
     *
     * @throws Exceptions\NotUniqueTaskFieldsException
     * @throws Exceptions\TaskNotFoundException
     * @throws Exceptions\CannotEditTaskException
     */
    public function edit(int $taskId, string $text): void
    {
        $task = $this->repository->getById($taskId);
        $task->edit($text);
        $this->repository->save($task, $taskId);
    }

    /**
     * @param int $taskId
     *
     * @throws Exceptions\NotUniqueTaskFieldsException
     * @throws Exceptions\TaskNotFoundException
     * @throws Exceptions\CannotDoneTaskException
     */
    public function done(int $taskId): void
    {
        $task = $this->repository->getById($taskId);
        $task->done();
        $this->repository->save($task, $task->getId()->getValue());
    }
}
