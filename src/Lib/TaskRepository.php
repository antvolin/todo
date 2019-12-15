<?php

namespace BeeJeeMVC\Lib;

use BeeJeeMVC\Model\Email;
use BeeJeeMVC\Model\Task;
use BeeJeeMVC\Model\Text;
use BeeJeeMVC\Model\UserName;
use Symfony\Component\Dotenv\Dotenv;

class TaskRepository
{
    /**
     * @var Task[]
     */
    private $taskList;

    /**
     * @var string
     */
    private $taskFolderPath;

    public function __construct()
    {
        $env = new Dotenv();
        $env->load(dirname(__DIR__).'/../.env');
        $this->taskFolderPath = dirname(__DIR__).$_ENV['TASK_FOLDER_NAME'];
    }

    /**
     * @param string $hash
     *
     * @return Task|null
     */
    public function getByHash(string $hash): ?Task
    {
        $file = file_get_contents($this->taskFolderPath.$hash);

        return unserialize($file, ['allowed_classes' => true]) ?? null;
    }

    /**
     * @param string|null $sortBy
     * @param string|null $orderBy
     *
     * @return array
     */
    public function getList(?string $sortBy = null, ?string $orderBy = null): array
    {
        $files = glob($this->taskFolderPath.'*');
        $tasks = [];
        foreach ($files as $file) {
            $tasks[basename($file)] = unserialize(file_get_contents($file), ['allowed_classes' => true]);
        }

        if ($sortBy && $orderBy) {
            $method = 'get'.ucfirst($sortBy);

            if (Sorting::ASC === $orderBy) {
                uasort($tasks, function (Task $a, Task $b) use ($method) {
                    return strcmp($a->$method(), $b->$method());
                });
            } else {
                uasort($tasks, function (Task $b, Task $a) use ($method) {
                    return strcmp($a->$method(), $b->$method());
                });
            }
        }

        return $tasks;
    }

    /**
     * @param string $userName
     * @param string $email
     * @param string $text
     *
     * @return Task
     */
    public function create(string $userName, string $email, string $text): Task
    {
        $task = new Task(new UserName($userName), new Email($email), new Text($text));
        $hash = (new HashGenerator())->generateHash($userName, $email, $text);

        file_put_contents($this->taskFolderPath.$hash, serialize($task));

        return $task;
    }

    /**
     * @param string $hash
     * @param string $newText
     *
     * @return Task|null
     */
    public function edit(string $hash, string $newText): ?Task
    {
        $task = $this->getByHash($hash);

        if ($task) {
            $task->edit($newText);
            rename($this->taskFolderPath.$hash, $this->taskFolderPath.$task->getHash());
            file_put_contents($this->taskFolderPath.$task->getHash(), serialize($task));
        }

        return $task;
    }

    /**
     * @param string $hash
     *
     * @return Task|null
     */
    public function done(string $hash): ?Task
    {
        $task = $this->getByHash($hash);

        if ($task) {
            $task->done();
            file_put_contents($this->taskFolderPath.$hash, serialize($task));
        }

        return $task;
    }
}
