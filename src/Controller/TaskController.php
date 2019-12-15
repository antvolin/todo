<?php

namespace BeeJeeMVC\Controller;

use BeeJeeMVC\Lib\TaskManager;
use BeeJeeMVC\Lib\Template;
use BeeJeeMVC\Lib\TemplateBuilder;
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
     * @var TaskManager
     */
    private $taskManager;

    /**
     * @var Template
     */
    private $template;

    /**
     * @param TaskManager $taskManager
     * @param Request $request
     * @param Template $template
     */
    public function __construct(TaskManager $taskManager, Request $request, Template $template)
    {
        $this->request = $request;
        $this->taskManager = $taskManager;
        $this->template = $template;
    }

    public function list(): void
    {
        $page = $this->request->get('page', 1);
        $sortBy = $this->request->get('sortBy');
        $orderBy = $this->request->get('orderBy');

        $builder = new TemplateBuilder();
        $content = $builder->buildList($page, $sortBy, $orderBy);

        echo $this->template->render('list', ['content' => $content]);
    }

    public function create(): void
    {
        if ('POST' !== $this->request->getMethod()) {
            echo $this->template->render('form_create');

            return;
        }

        if ($_SESSION['admin']) {
            echo $this->template->render('form_create', ['error' => self::NOT_ENOUGH_RIGHTS_MSG]);

            return;
        }

        $userName = $this->request->request->filter('userName', null, FILTER_SANITIZE_SPECIAL_CHARS);
        $email = $this->request->request->filter('email', null, FILTER_SANITIZE_SPECIAL_CHARS);
        $text = $this->request->request->filter('text', null, FILTER_SANITIZE_SPECIAL_CHARS);

        try {
            $this->taskManager->save($userName, $email, $text);

            echo $this->template->render('created');
        } catch (InvalidArgumentException $exception) {
            echo $this->template->render('form_create', ['error' => $exception->getMessage()]);
        }
    }

    public function edit(): void
    {
        if ('POST' !== $this->request->getMethod()) {
            echo $this->template->render('form_edit', ['hash' => func_get_args()[0]]);

            return;
        }
        if (!$_SESSION['admin']) {
            echo $this->template->render('edit_error', ['error' => self::NOT_ENOUGH_RIGHTS_MSG]);

            return;
        }

        $id = $this->request->request->filter('id', null, FILTER_SANITIZE_SPECIAL_CHARS);
        $text = $this->request->request->filter('text', null, FILTER_SANITIZE_SPECIAL_CHARS);

        try {
            $this->taskManager->edit($id, $text);

            echo $this->template->render('edited');
        } catch (InvalidArgumentException $exception) {
            echo $this->template->render('edit_error', ['error' => $exception->getMessage()]);
        }
    }

    public function done(): void
    {
        if (!$_SESSION['admin']) {
            echo $this->template->render('done_error', ['error' => self::NOT_ENOUGH_RIGHTS_MSG]);

            return;
        }

        try {
            $this->taskManager->done(func_get_args()[0]);

            echo $this->template->render('done');
        } catch (InvalidArgumentException $exception) {
            echo $this->template->render('done_error', ['error' => $exception->getMessage()]);
        }
    }
}
