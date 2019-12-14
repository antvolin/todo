<?php

namespace BeeJeeMVC;

class PageController
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $base;

    public function __construct()
    {
        $this->base = '/';
        $this->name = 'Page';
    }

    public function taskList(): void
    {
        $repo = new TaskRepository();
        $builder = new Builder($this->name, $this->base);
        $content = $builder->buildList($repo->getList());

        include_once('list.html');
    }

    public function create(array $taskData): void
    {
        $content = (new User())->createTask($taskData[0], $taskData[1], $taskData[2]);

        include_once('create.html');
    }
}
