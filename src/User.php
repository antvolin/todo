<?php

namespace BeeJeeMVC;

class User
{
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
