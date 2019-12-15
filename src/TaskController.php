<?php

namespace BeeJeeMVC;

use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\View\DefaultView;

class TaskController
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $base;

    /**
     * @var Request
     */
    private $request;

    public function __construct()
    {
        $this->base = '/';
        $this->name = 'Task';
        $this->request = Request::createFromGlobals();
    }

    public function list(): void
    {
        $page = !$this->request->get('page') ? : 1;
        $sortBy = $this->request->get('sortBy');
        $orderBy = $this->request->get('orderBy');

        $tasks = (new TaskRepository())->getList($sortBy, $orderBy);
        $pager = new Pagerfanta(new ArrayAdapter($tasks));
        $pager->setMaxPerPage(3);
        $pager->setCurrentPage($page);

        $routeGenerator = function($page) use ($sortBy, $orderBy)
        {
            $sortBy = $sortBy ? '&sortBy='.$sortBy : '';
            $orderBy = $orderBy ? '&orderBy='.$orderBy : '';

            return '/?route=task/list&page='.$page.$sortBy.$orderBy;
        };
        $html = (new DefaultView())->render($pager, $routeGenerator);

        $builder = new Builder($this->name, $this->base);
        $content = $builder->buildList($pager->getCurrentPageResults(), $page, $orderBy);

        include_once('list.html');
    }

    public function create(): void
    {
        if ('POST' === $this->request->getMethod()) {
            if (!$_SESSION['admin']) {
                $userName = $this->request->get('userName');
                $email = $this->request->get('email');
                $text = $this->request->get('text');

                try {
                    $task = (new TaskRepository())->create($userName, $email, $text);
                    $content = 'Task #'.$task.'created!';

                    include_once('created.html');
                } catch (InvalidArgumentException $exception) {
                    $error = $exception->getMessage();

                    include_once('form_create.html');
                }
            } else {
                $error = 'Insufficient rights for this operation!';

                include_once('form_create.html');
            }
        } else {
            include_once('form_create.html');
        }
    }

    public function edit(): void
    {
        if ('POST' === $this->request->getMethod()) {
            if ($_SESSION['admin']) {
                $hash = $this->request->get('hash');
                $key = $this->request->get('text');

                (new TaskRepository())->edit($hash, $key);

                include_once('edited.html');
            } else {
                $error = 'Insufficient rights for this operation!';

                include_once('edited_error.html');
            }
        } else {
            $hash = func_get_args()[0];

            include_once('form_edit.html');
        }
    }

    public function done(): void
    {
        if ($_SESSION['admin']) {
            (new TaskRepository())->done(func_get_args()[0]);

            include_once('done.html');
        } else {
            $error = 'Insufficient rights for this operation!';

            include_once('done_error.html');
        }
    }
}
