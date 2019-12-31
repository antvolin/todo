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
    public function getById(string $id): Task
    {
        $sth = $this->pdo->prepare('SELECT * FROM task WHERE id = ?;');
        $sth->bindParam(1, $id);
        $sth->execute();

        if (!$task = $sth->fetch(PDO::FETCH_ASSOC)) {
            throw new TaskNotFoundException();
        }

        return new Task(new UserName($task['user_name']), new Email($task['email']), new Text($task['text']));
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

        $key = array_search($orderBy, Ordering::ALLOWED_ORDER_FIELDS, true);
        $orderBy = Ordering::ALLOWED_ORDER_FIELDS[$key];
        $order = $order === 'DESC' ? 'DESC' : 'ASC';

        $sth = $this->pdo->prepare("SELECT * FROM task ORDER BY $orderBy $order LIMIT ? OFFSET ?;");
        $limit = $this->tasksPerPage;
        $offset = $this->tasksPerPage * ($page - 1);
        $sth->bindParam(1, $limit, PDO::PARAM_INT);
        $sth->bindParam(2, $offset, PDO::PARAM_INT);
        $sth->execute();

        foreach ($sth->fetchAll(PDO::FETCH_ASSOC) as $task) {
            $result[$task['id']] = new Task(new UserName($task['user_name']), new Email($task['email']), new Text($task['text']));
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function save(Task $task): void
    {
        $taskId = $task->getId();
        $userName = $task->getUserName();
        $email = $task->getEmail();
        $text = $task->getText();

        $sth = $this->pdo->prepare('INSERT INTO task (id, user_name, email, text) VALUES(?, ?, ?, ?);');
        $sth->bindParam(1, $taskId);
        $sth->bindParam(2, $userName);
        $sth->bindParam(3, $email);
        $sth->bindParam(4, $text);

        try {
            $sth->execute();
        } catch (PDOException $exception) {
            throw new NotUniqueTaskFieldsException();
        }
    }
}
