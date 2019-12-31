<?php

namespace BeeJeeMVC\Lib\Repository;

use BeeJeeMVC\Lib\Db;
use BeeJeeMVC\Lib\Exceptions\NotUniqueTaskFieldsException;
use BeeJeeMVC\Lib\Exceptions\TaskNotFoundException;
use BeeJeeMVC\Lib\Ordering;
use BeeJeeMVC\Model\Email;
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
        $this->pdo = (new Db())->getPdo();
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

        $taskObj = new Task(new UserName($task['user_name']), new Email($task['email']), new Text($task['text']));
        $taskObj->setId($task['id']);

        return $taskObj;
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
            $taskObj = new Task(new UserName($task['user_name']), new Email($task['email']), new Text($task['text']));
            $taskObj->setId($task['id']);
            $result[$task['id']] = $taskObj;
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

        if ($taskId) {
            $sth = $this->pdo->prepare('UPDATE task SET user_name = :userName, email = :email, text = :text WHERE id = :id;');
            $sth->bindParam(':id', $taskId, PDO::PARAM_INT);
        } else {
            $sth = $this->pdo->prepare('INSERT INTO task (user_name, email, text) VALUES(:userName, :email, :text);');
        }

        $sth->bindParam(':userName', $userName);
        $sth->bindParam(':email', $email);
        $sth->bindParam(':text', $text);

        try {
            $sth->execute();
        } catch (PDOException $exception) {
            throw new NotUniqueTaskFieldsException();
        }
    }
}
