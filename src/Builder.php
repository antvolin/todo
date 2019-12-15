<?php

namespace BeeJeeMVC;

class Builder
{
    /**
     * @var string
     */
	private $controller;

    /**
     * @var string
     */
    private $base;

    /**
     * @param string $name
     * @param string $base
     */
	public function __construct(string $name, string $base)
	{
		$this->controller = $name;
        $this->base = $base;
	}

    /**
     * @param array $arrayTask
     * @param int $page
     * @param string|null $orderBy
     *
     * @return string
     */
	public function buildList(array $arrayTask, int $page, ?string $orderBy): string
    {
		$content = $this->buildSorting($page, $orderBy);

		foreach ($arrayTask as $task) {
			$content .= $this->buildTask($task);
		}

        if ($_SESSION['admin']) {
            $content .= '<div><a href="/?route=auth/logout">Logout</a></div>';
        } else {
            $content .= '<div><a href="/?route=task/create">Create task</a></div>';
            $content .= '<div><a href="/?route=auth/login">Login</a></div>';
        }

		return $content;
	}

    /**
     * @param int $page
     * @param string|null $orderBy
     *
     * @return string
     */
	private function buildSorting(int $page, ?string $orderBy): string
    {
        $orderBy = $this->getNextOrder($orderBy);

        return '<div class="sorting">
            <div class="userName"><a href="/?route=task/list&page='.$page.'&sortBy=userName&orderBy='.$orderBy.'">User name</a></div>
            <div class="email"><a href="/?route=task/list&page='.$page.'&sortBy=email&orderBy='.$orderBy.'">Email</a></div>
            <div class="text"><a href="/?route=task/list&page='.$page.'&sortBy=text&orderBy='.$orderBy.'">Text</a></div>
        </div>';
    }

    /**
     * @param string $orderBy
     *
     * @return string
     */
    private function getNextOrder(string $orderBy): string
    {
        return !$orderBy || 'ASC' === $orderBy ? 'DESC' : 'ASC';
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
				<div class="userName">'.$task->getUserName().'</div>
				<div class="email">'.$task->getEmail().'</div>
				<div class="text">'.$task->getText().'</div>
				<div class="status">'.$task->getStatus().'</div>'.
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
