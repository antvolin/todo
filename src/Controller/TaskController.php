<?php

namespace BeeJeeMVC\Controller;

use BeeJeeMVC\Lib\Builder;
use BeeJeeMVC\Lib\TaskRepository;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;

class TaskController
{
    private const FIRST_PAGE = 1;
    private const NOT_ENOUGH_RIGHTS_MSG = 'Not enough rights for this operation!';

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
        $page = $this->request->get('page') ? : self::FIRST_PAGE;
        $sortBy = $this->request->get('sortBy');
        $orderBy = $this->request->get('orderBy');

        $builder = new Builder($this->name, $this->base);
        $content = $builder->buildList($page, $sortBy, $orderBy);

        include_once(dirname(__DIR__).'/View/list.html');
    }

    public function create(): void
    {
        if ('POST' === $this->request->getMethod()) {
            if (!$_SESSION['admin']) {
                $userName = $this->request->request->filter('userName', null, FILTER_SANITIZE_SPECIAL_CHARS);
                $email = $this->request->request->filter('email', null, FILTER_SANITIZE_SPECIAL_CHARS);
                $text = $this->request->request->filter('text', null, FILTER_SANITIZE_SPECIAL_CHARS);

                try {
                    $task = (new TaskRepository())->create($userName, $email, $text);
                    $content = 'Task #'.$task.' created!';
                    include_once(dirname(__DIR__).'/View/created.html');
                } catch (InvalidArgumentException $exception) {
                    $error = $exception->getMessage();
                    include_once(dirname(__DIR__).'/View/form_create.html');
                }
            } else {
                $error = self::NOT_ENOUGH_RIGHTS_MSG;
                include_once(dirname(__DIR__).'/View/form_create.html');
            }
        } else {
            include_once(dirname(__DIR__).'/View/form_create.html');
        }
    }

    public function edit(): void
    {
        if ('POST' === $this->request->getMethod()) {
            if ($_SESSION['admin']) {
                $hash = $this->request->request->filter('hash', null, FILTER_SANITIZE_SPECIAL_CHARS);
                $text = $this->request->request->filter('text', null, FILTER_SANITIZE_SPECIAL_CHARS);

                try {
                    $task = (new TaskRepository())->edit($hash, $text);
                    $content = 'Task #'.$task.' edited!';
                    include_once(dirname(__DIR__).'/View/edited.html');
                } catch (InvalidArgumentException $exception) {
                    $error = $exception->getMessage();
                    include_once(dirname(__DIR__).'/View/edit_error.html');
                }
            } else {
                $error = self::NOT_ENOUGH_RIGHTS_MSG;
                include_once(dirname(__DIR__).'/View/edit_error.html');
            }
        } else {
            $hash = func_get_args()[0];
            include_once(dirname(__DIR__).'/View/form_edit.html');
        }
    }

    public function done(): void
    {
        if ($_SESSION['admin']) {
            try {
                $task = (new TaskRepository())->done(func_get_args()[0]);
                $content = 'Task #'.$task.' done!';
                include_once(dirname(__DIR__).'/View/done.html');
            } catch (InvalidArgumentException $exception) {
                $error = $exception->getMessage();
                include_once(dirname(__DIR__).'/View/done_error.html');
            }
        } else {
            $error = self::NOT_ENOUGH_RIGHTS_MSG;
            include_once(dirname(__DIR__).'/View/done_error.html');
        }
    }
}
