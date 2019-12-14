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
     * @param string $hash
     */
    public function doneTask(string $hash): void
    {
        $taskRepo = new TaskRepository();

        $taskRepo->done($hash);
    }
}
