<?php

namespace BeeJeeMVC\Controller;

use BeeJeeMVC\Lib\TemplateBuilder;
use BeeJeeMVC\Lib\RepositoryInterface;
use BeeJeeMVC\Model\Email;
use BeeJeeMVC\Model\Task;
use BeeJeeMVC\Model\Text;
use BeeJeeMVC\Model\UserName;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;

class TaskController
{
    private const FIRST_PAGE = 1;
    private const NOT_ENOUGH_RIGHTS_MSG = 'Not enough rights for this operation!';

    /**
     * @var Request
     */
    private $request;

    /**
     * @var RepositoryInterface
     */
    private $repository;

    /**
     * @param RepositoryInterface $repository
     * @param Request $request
     */
    public function __construct(RepositoryInterface $repository, Request $request)
    {
        $this->request = $request;
        $this->repository = $repository;
    }

    public function list(): void
    {
        $page = $this->request->get('page') ? : self::FIRST_PAGE;
        $sortBy = $this->request->get('sortBy');
        $orderBy = $this->request->get('orderBy');

        $builder = new TemplateBuilder();
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
                    $task = $this->repository->save(new Task(new UserName($userName), new Email($email), new Text($text)));
                    $content = 'Task #'.$task->getId().' created!';
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
                $hash = $this->request->request->filter('id', null, FILTER_SANITIZE_SPECIAL_CHARS);
                $text = $this->request->request->filter('text', null, FILTER_SANITIZE_SPECIAL_CHARS);

                try {
                    $task = $this->repository->getById($hash);
                    $task->edit($text);
                    $this->repository->save($task);

                    $content = 'Task #'.$task->getId().' edited!';
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
                $task = $this->repository->getById(func_get_args()[0]);
                $task->done();
                $this->repository->save($task);

                $content = 'Task #'.$task->getId().' done!';
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
