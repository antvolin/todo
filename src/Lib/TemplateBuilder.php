<?php

namespace BeeJeeMVC\Lib;

use BeeJeeMVC\Model\Task;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\View\DefaultView;

class TemplateBuilder
{
    /**
     * @var string
     */
    private $taskFolderPath;

    /**
     * @var bool
     */
    private $isAdmin;

    /**
     * @param string $taskFolderPath
     * @param bool $isAdmin
     */
    public function __construct(string $taskFolderPath, bool $isAdmin)
    {
        $this->taskFolderPath = $taskFolderPath;
        $this->isAdmin = $isAdmin;
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
        if ($this->isAdmin) {
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
        $tasks = (new TaskFileTaskRepository($this->taskFolderPath))->getList($sortBy, $orderBy);
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
        return '<div id="'.$task->getId().'" class="row">
            <div class="col-sm">'.$task->getUserName().'</div>
            <div class="col-sm">'.$task->getEmail().'</div>
            <div class="col-sm">'.$task->getText().'</div>
            <div class="col-sm">'.$this->getStatus($task).'</div>'.
            '<div class="col-sm">'.$this->createEditLink($task->getId()).$this->createDoneLink($task->getId()).'</div>'.
        '</div>';
	}

    /**
     * @param Task $task
     *
     * @return string
     */
	private function getStatus(Task $task): string
    {
        $status = '';

        if ($task->isEdited()) {
            $status .= ' edited ';
        }
        if ($task->isDone()) {
            $status .= ' done ';
        }

        return $status;
    }

    /**
     * @param string $hash
     *
     * @return string
     */
	private function createEditLink(string $hash): string
    {
        $link = '';

        if ($this->isAdmin) {
            $link = '<button><a href="?route=/task/edit/'.$hash.'">Edit task</a></button>';
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

        if ($this->isAdmin) {
            $link = '<button><a href="?route=/task/done/'.$hash.'">Done task</a></button>';
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
        $routeGenerator = function (int $page) use ($sortBy, $orderBy) {
            return '/?route=task/list&page='.$page.'&sortBy='.$sortBy.'&orderBy='.$orderBy;
        };

        return '<div class="pages">'.(new DefaultView())->render($pager, $routeGenerator).'</div>';
    }
}
