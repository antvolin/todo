<?php

namespace BeeJeeMVC\Lib;

use BeeJeeMVC\Model\Task;

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

        if ($_SESSION['admin']) {
            $content = '<div><a href="/?route=auth/logout">Logout</a></div>';
        } else {
            $content = '<div class="buttons"><button class="button"><a href="/?route=task/create">Create task</a></button>';
            $content .= '<button class="button"><a href="/?route=auth/login">Login</a></button></div>';
        }

        $content .= $this->buildSorting($page, $orderBy);

        foreach ($arrayTask as $task) {
            $content .= $this->buildTask($task);
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

        return '<div class="row">
            <div class="col-sm"><a href="/?route=task/list&page='.$page.'&sortBy=userName&orderBy='.$orderBy.'">User name</a></div>
            <div class="col-sm"><a href="/?route=task/list&page='.$page.'&sortBy=email&orderBy='.$orderBy.'">Email</a></div>
            <div class="col-sm"><a href="/?route=task/list&page='.$page.'&sortBy=text&orderBy='.$orderBy.'">Text</a></div>
            <div class="col-sm">Status</div>
            <div class="col-sm"></div>
        </div>';
    }

    /**
     * @param string|null $orderBy
     *
     * @return string
     */
    private function getNextOrder(?string $orderBy): string
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
			'<div id="$task->getHash()" class="row">
				<div class="col-sm">'.$task->getUserName().'</div>
				<div class="col-sm">'.$task->getEmail().'</div>
				<div class="col-sm">'.$task->getText().'</div>
				<div class="col-sm">'.$task->getStatus().'</div>'.
                '<div class="col-sm">'.$this->createEditLink($task->getHash()).$this->createDoneLink($task->getHash()).'</div>'.
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
