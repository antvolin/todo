<?php

namespace BeeJeeMVC;

use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;

class PageController
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
        $this->name = 'Page';
        $this->request = Request::createFromGlobals();
    }

    public function list(): void
    {
        $repo = new TaskRepository();
        $builder = new Builder($this->name, $this->base);
        $content = $builder->buildList($repo->getList());

        include_once('list.html');
    }

    public function create(): void
    {
        if ('POST' === $this->request->getMethod()) {
            if (!$_SESSION['admin']) {
                $userName = $this->request->get('user_name');
                $email = $this->request->get('email');
                $text = $this->request->get('text');

                try {
                    $content = 'Task #' . (new User())->createTask($userName, $email, $text) . 'created!';
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
                $text = $this->request->get('text');

                (new Admin())->editTask($hash, $text);

                include_once('edit.html');
            } else {
                $repo = new TaskRepository();
                $builder = new Builder($this->name, $this->base);
                $error = 'Insufficient rights for this operation!';
                $content = $builder->buildList($repo->getList());

                include_once('list.html');
            }
        } else {
            $hash = func_get_args()[0];

            include_once('form_edit.html');
        }
    }

    public function done(): void
    {
        if ($_SESSION['admin']) {
            (new Admin())->doneTask(func_get_args()[0]);
        } else {
            $error = 'Insufficient rights for this operation!';
        }

        $repo = new TaskRepository();
        $builder = new Builder($this->name, $this->base);
        $content = $builder->buildList($repo->getList());

        include_once('list.html');
    }
}
