<?php

namespace BeeJeeMVC;

class Admin
{
    /**
     * @param Task $task
     * @param string $newText
     */
    public function editTask(Task $task, string $newText): void
    {
        $taskRepo = new TaskRepository();

        $taskRepo->edit($task, $newText);
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
