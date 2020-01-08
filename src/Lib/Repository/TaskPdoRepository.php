<?php

namespace BeeJeeMVC\Lib\Repository;

use BeeJeeMVC\Lib\PdoManager;
use BeeJeeMVC\Lib\Exceptions\NotUniqueTaskFieldsException;
use BeeJeeMVC\Lib\Exceptions\TaskNotFoundException;
use BeeJeeMVC\Lib\Ordering;
use BeeJeeMVC\Model\Email;
use BeeJeeMVC\Model\Id;
use BeeJeeMVC\Model\Status;
use BeeJeeMVC\Model\Task;
use BeeJeeMVC\Model\Text;
use BeeJeeMVC\Model\UserName;
use PDO;
use PDOException;

class TaskPdoRepository implements TaskRepositoryInterface
{
    /**
     * @var int
     */
    private $tasksPerPage;

    /**
     * @var \PDO
     */
    private $pdo;

    /**
     * @param int $tasksPerPage
     */
    public function __construct(int $tasksPerPage)
    {
        $this->pdo = (new PdoManager())->getPdo();
        $this->tasksPerPage = $tasksPerPage;
    }

    /**
     * @inheritdoc
     */
    public function getById(int $id): Task
    {
        $sth = $this->pdo->prepare('SELECT * FROM task WHERE id = :id;');
        $sth->bindParam(':id', $id, PDO::PARAM_INT);
        $sth->execute();

        if (!$task = $sth->fetch(PDO::FETCH_ASSOC)) {
            throw new TaskNotFoundException();
        }

        return $this->createTask($task);
    }

    /**
     * @inheritdoc
     */
    public function getCountRows(): int
    {
        return  $this->pdo->query('SELECT COUNT(id) FROM task;')->fetchColumn();
    }

    /**
     * @inheritdoc
     */
    public function getList(int $page, ?string $orderBy = null, ?string $order = null): array
    {
        $result = [];

        $orderBy = Ordering::getOrderBy($orderBy);
        $order = Ordering::getOrder($order);

        $sth = $this->pdo->prepare("SELECT * FROM task ORDER BY $orderBy $order LIMIT :limit OFFSET :offset;");
        $limit = $this->tasksPerPage;
        $offset = $this->tasksPerPage * ($page - 1);
        $sth->bindParam(':limit', $limit, PDO::PARAM_INT);
        $sth->bindParam(':offset', $offset, PDO::PARAM_INT);
        $sth->execute();

        foreach ($sth->fetchAll(PDO::FETCH_ASSOC) as $task) {
            $result[$task['id']] = $this->createTask($task);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function save(Task $task, ?int $taskId = null): void
    {
        $userName = $task->getUserName();
        $email = $task->getEmail();
        $text = $task->getText();
        $status = $task->getStatus();

        if ($taskId) {
            $sth = $this->pdo->prepare('UPDATE task SET user_name = :userName, email = :email, text = :text, status = :status WHERE id = :id;');
            $sth->bindParam(':id', $taskId, PDO::PARAM_INT);
        } else {
            $sth = $this->pdo->prepare('INSERT INTO task (user_name, email, text, status) VALUES(:userName, :email, :text, :status);');
        }

        $sth->bindParam(':userName', $userName);
        $sth->bindParam(':email', $email);
        $sth->bindParam(':text', $text);
        $sth->bindParam(':status', $status);

        try {
            $sth->execute();
        } catch (PDOException $exception) {
            throw new NotUniqueTaskFieldsException();
        }
    }

    /**
     * @param array $task
     *
     * @return Task
     *
     * @throws \BeeJeeMVC\Lib\Exceptions\CannotBeEmptyException
     * @throws \BeeJeeMVC\Lib\Exceptions\ForbiddenStatusException
     * @throws \BeeJeeMVC\Lib\Exceptions\NotValidEmailException
     */
    private function createTask(array $task): Task
    {
        return new Task(
            new Id($task['id']),
            new UserName($task['user_name']),
            new Email($task['email']),
            new Text($task['text']),
            new Status($task['status'])
        );
    }
}