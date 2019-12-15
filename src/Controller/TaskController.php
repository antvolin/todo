<?php

namespace BeeJeeMVC\Controller;

use BeeJeeMVC\Lib\Template;
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
        $page = $this->request->get('page', 1);
        $sortBy = $this->request->get('sortBy');
        $orderBy = $this->request->get('orderBy');

        $builder = new TemplateBuilder();
        $content = $builder->buildList($page, $sortBy, $orderBy);

        echo (new Template())->render('list', ['content' => $content]);
    }

    public function create(): void
    {
        if ('POST' !== $this->request->getMethod()) {
            echo (new Template())->render('form_create');

            return;
        }

        if ($_SESSION['admin']) {
            echo (new Template())->render('form_create', ['error' => self::NOT_ENOUGH_RIGHTS_MSG]);

            return;
        }

        $userName = $this->request->request->filter('userName', null, FILTER_SANITIZE_SPECIAL_CHARS);
        $email = $this->request->request->filter('email', null, FILTER_SANITIZE_SPECIAL_CHARS);
        $text = $this->request->request->filter('text', null, FILTER_SANITIZE_SPECIAL_CHARS);

        try {
            $task = $this->repository->save(new Task(new UserName($userName), new Email($email), new Text($text)));

            echo (new Template())->render('created', ['content' => 'Task #'.$task->getId().' created!']);
        } catch (InvalidArgumentException $exception) {
            echo (new Template())->render('form_create', ['error' => $exception->getMessage()]);
        }
    }

    public function edit(): void
    {
        if ('POST' !== $this->request->getMethod()) {
            echo (new Template())->render('form_edit', ['hash' => func_get_args()[0]]);

            return;
        }
        if (!$_SESSION['admin']) {
            echo (new Template())->render('edit_error', ['error' => self::NOT_ENOUGH_RIGHTS_MSG]);

            return;
        }

        $hash = $this->request->request->filter('id', null, FILTER_SANITIZE_SPECIAL_CHARS);
        $text = $this->request->request->filter('text', null, FILTER_SANITIZE_SPECIAL_CHARS);

        try {
            $task = $this->repository->getById($hash);
            $task->edit($text);
            $this->repository->save($task);

            echo (new Template())->render('edited', ['content' => 'Task #'.$task->getId().' edited!']);
        } catch (InvalidArgumentException $exception) {
            echo (new Template())->render('edit_error', ['error' => $exception->getMessage()]);
        }
    }

    public function done(): void
    {
        if (!$_SESSION['admin']) {
            echo (new Template())->render('done_error', ['error' => self::NOT_ENOUGH_RIGHTS_MSG]);

            return;
        }

        try {
            $task = $this->repository->getById(func_get_args()[0]);
            $task->done();
            $this->repository->save($task);

            echo (new Template())->render('done', ['content' => 'Task #'.$task->getId().' done!']);
        } catch (InvalidArgumentException $exception) {
            echo (new Template())->render('done_error', ['error' => $exception->getMessage()]);
        }
    }
}
