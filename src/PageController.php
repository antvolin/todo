<?php

namespace BeeJeeMVC;

use BeeJeeMVC\TaskRepository;

class PageController
{
    public function taskList(): void
    {
        $repo = new TaskRepository();

        $content = $repo->getList();

        include_once('list.html');
    }

    public function create(): void
    {
        $repo = new TaskRepository();
        $userName = new UserName('user 1');
        $email = new Email('test@tes.com');
        $text = new Text('text');

        $content = $repo->create($userName, $email, $text);

        include_once('create.html');
    }
}
