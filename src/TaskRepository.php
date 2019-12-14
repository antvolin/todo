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
            'f928bb3caaf876878d8dc172324bfd3acreated' => new Task(new UserName('test2'), new Email('test@test.com'), new Text('text2')),
            '853c962407c926a5a16479438bf45c40created' => new Task(new UserName('test1'), new Email('test@test.com'), new Text('tttt')),
            '5949b6e77c7cedeeeac18f12e38fbb6fcreated' => new Task(new UserName('test1'), new Email('test@test.com'), new Text('asdasd')),
            '2daebe4112efe83e565aecb9c2632238created' => new Task(new UserName('test2'), new Email('test@test.com'), new Text('asdasd')),
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
