<?php

namespace BeeJeeMVC;

class Builder
{
	private $controller;
    private $base;

	public function __construct($name, $base)
	{
		$this->controller = $name;
        $this->base = $base;
	}

    /**
     * @param $arrayTask
     *
     * @return string
     */
	public function buildList($arrayTask): string
    {
		$content = '';

		foreach ($arrayTask as $task) {
			$content .= $this->buildTask($task);
		}

        if ($_SESSION['admin']) {
            $content .= '<div><a href="/?route=auth/logout">Logout</a></div>';
        } else {
            $content .= '<div><a href="/?route=page/create">Create task</a></div>';
            $content .= '<div><a href="/?route=auth/login">Login</a></div>';
        }

		return $content;
	}

    /**
     * @param Task $task
     *
     * @return string
     */
	private function buildTask(Task $task): string
    {
		$content =
			'<div id="$task->getHash()" class="task">
				<div class="userName">' . $task->getUserName() . '</div>
				<div class="email">' . $task->getEmail() . '</div>
				<div class="text">' . $task->getText() . '</div>
				<div class="status">' . $task->getStatus() . '</div>'.
                $this->createEditLink($task->getHash()).
                $this->createDoneLink($task->getHash()).
			'</div>';

		return $content;
	}

    /**
     * @param string $hash
     *
     * @return string
     */
	private function createEditLink(string $hash): string
    {
        $link = '';

        if ($_SESSION['admin']) {
            $link = '<a href="?route='.$this->base.strtolower($this->controller).'/edit/'.$hash.'">Edit task</a><br/>';
        }

        return $link;
    }

    /**
     * @param string $hash
     *
     * @return string
     */
    private function createDoneLink(string $hash): string
    {
        $link = '';

        if ($_SESSION['admin']) {
            $link = '<a href="?route='.$this->base.strtolower($this->controller).'/done/'.$hash.'">Done task</a><br/>';
        }

        return $link;
    }
}
