<?php

namespace BeeJeeMVC;

class User
{
    /**
     * @return array
     */
    public function getTaskList(): array
    {
        $taskRepo = new TaskRepository();

        return $taskRepo->getList();
    }

    /**
     * @param string $userName
     * @param string $email
     * @param string $text
     *
     * @return Task
     */
    public function createTask(string $userName, string $email, string $text): Task
    {
        $taskRepo = new TaskRepository();

        return $taskRepo->create(new UserName($userName), new Email($email), new Text($text));
    }
}
