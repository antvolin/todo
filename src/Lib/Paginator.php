<?php

namespace BeeJeeMVC\Lib;

use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\View\DefaultView;

class Paginator
{
    /**
     * @var Pagerfanta
     */
    private $pager;

    /**
     * @param int $page
     * @param array $rows
     */
    public function createPager(int $page, array $rows): void
    {
        if (!$this->pager) {
            $pager = new Pagerfanta(new ArrayAdapter($rows));
            $pager->setMaxPerPage($_ENV['TASKS_PER_PAGE']);
            $pager->setCurrentPage($page);

            $this->pager = $pager;
        }
    }

    /**
     * @return array|\Traversable
     */
    public function getCurrentPageTasks()
    {
        return $this->pager->getCurrentPageResults();
    }

    /**
     * @param string|null $sortBy
     * @param string|null $orderBy
     *
     * @return string
     */
    public function getPagination(?string $sortBy, ?string $orderBy): string
    {
        $routeGenerator = function (int $page) use ($sortBy, $orderBy) {
            return sprintf('/task/list?page=%s&sortBy=%s&orderBy=%s', $page, $sortBy, $orderBy);
        };

        return (new DefaultView())->render($this->pager, $routeGenerator);
    }
}
