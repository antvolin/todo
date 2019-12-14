<?php

namespace BeeJeeMVC;

class Admin
{
    /**
     * @param string $hash
     * @param string $newText
     */
    public function editTask(string $hash, string $newText): void
    {
        $taskRepo = new TaskRepository();

        $taskRepo->edit($hash, $newText);
    }

    /**
     * @param Task $task
     */
    public function doneTask(Task $task): void
    {
        $taskRepo = new TaskRepository();

        $taskRepo->done($task);
    }
}
