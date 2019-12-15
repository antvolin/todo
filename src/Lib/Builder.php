<?php

namespace BeeJeeMVC\Lib;

use BeeJeeMVC\Model\Task;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\View\DefaultView;
use Symfony\Component\Dotenv\Dotenv;

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
     * @param int $page
     * @param string|null $sortBy
     * @param string|null $orderBy
     *
     * @return string
     */
	public function buildList(int $page, ?string $sortBy, ?string $orderBy): string
    {
        $content = $this->buildButtons();
        $content .= $this->buildSorting($page, $orderBy);
        $pager = $this->createPager($page, $sortBy, $orderBy);

        foreach ($pager->getCurrentPageResults() as $task) {
            $content .= $this->buildTask($task);
        }

        $content .= $this->buildPagination($pager, $sortBy, $orderBy);

		return $content;
	}

    /**
     * @return string
     */
	private function buildButtons(): string
    {
        if ($_SESSION['admin']) {
            $content = '<div class="buttons"><button class="button"><a href="/?route=auth/logout">Logout</a></button></div>';
        } else {
            $content = '<div class="buttons"><button class="button"><a href="/?route=task/create">Create task</a></button>';
            $content .= '<button class="button"><a href="/?route=auth/login">Login</a></button></div>';
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
        return !$orderBy || Sorting::ASC === $orderBy ? Sorting::DESC : Sorting::ASC;
    }

    /**
     * @param int $page
     * @param string|null $sortBy
     * @param string|null $orderBy
     *
     * @return Pagerfanta
     */
    private function createPager(int $page, ?string $sortBy, ?string $orderBy): Pagerfanta
    {
        $env = new Dotenv();
        $env->load(dirname(__DIR__).'/../.env');

        $tasks = (new TaskFileRepository())->getList($sortBy, $orderBy);
        $pager = new Pagerfanta(new ArrayAdapter($tasks));
        $pager->setMaxPerPage($_ENV['TASKS_PER_PAGE']);
        $pager->setCurrentPage($page);

        return $pager;
    }

    /**
     * @param Task $task
     *
     * @return string
     */
	private function buildTask(Task $task): string
    {
		$content =
			'<div id="'.$task.'" class="row">
				<div class="col-sm">'.$task->getUserName().'</div>
				<div class="col-sm">'.$task->getEmail().'</div>
				<div class="col-sm">'.$task->getText().'</div>
				<div class="col-sm">'.$task->getStatus().'</div>'.
                '<div class="col-sm">'.$this->createEditLink($task).$this->createDoneLink($task).'</div>'.
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
            $link = '<button><a href="?route='.$this->base.strtolower($this->controller).'/edit/'.$hash.'">Edit task</a></button>';
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
            $link = '<button><a href="?route='.$this->base.strtolower($this->controller).'/done/'.$hash.'">Done task</a></button>';
        }

        return $link;
    }

    /**
     * @param Pagerfanta $pager
     * @param string|null $sortBy
     * @param string|null $orderBy
     *
     * @return string
     */
    private function buildPagination(Pagerfanta $pager, ?string $sortBy, ?string $orderBy): string
    {
        $routeGenerator = function(int $page) use ($sortBy, $orderBy)
        {
            return '/?route=task/list&page='.$page.'&sortBy='.$sortBy.'&orderBy='.$orderBy;
        };

        return '<div class="pages">'.(new DefaultView())->render($pager, $routeGenerator).'</div>';
    }
}
