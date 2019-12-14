<?php

namespace BeeJeeMVC;

class TaskRepository
{
    /**
     * @var Task[]
     */
    private $taskList;

    public function __construct()
    {
        $this->taskList = [
            '17f072526080bf7daa7b742c1dd94ec8' => new Task(new UserName('test1'), new Email('test@test.com'), new Text('text1')),
        ];
    }

    /**
     * @param string $hash
     *
     * @return Task|null
     */
    public function getByHash(string $hash): ?Task
    {
        return $this->taskList[$hash] ?? null;
    }

    /**
     * @return Task[]
     */
    public function getList(): array
    {
        return $this->taskList;
    }

    /**
     * @param UserName $userName
     * @param Email $email
     * @param Text $text
     *
     * @return Task
     */
    public function create(UserName $userName, Email $email, Text $text): Task
    {
        $task = new Task($userName, $email, $text);
        $hash = (new HashGenerator())->generateHash($userName, $email, $text);

        $this->taskList[$hash] = $task;

        return $task;
    }

    /**
     * @param string $hash
     * @param string $newText
     */
    public function edit(string $hash, string $newText): void
    {
        $task = $this->getByHash($hash);

        if ($task) {
            $task->edit($newText);
            $this->taskList[$task->getHash()] = $task;
        }
    }

    /**
     * @param string $hash
     */
    public function done(string $hash): void
    {
        $task = $this->getByHash($hash);

        if ($task) {
            $task->done();
            $this->taskList[$task->getHash()] = $task;
        }
    }
}
