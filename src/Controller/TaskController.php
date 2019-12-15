<?php

namespace BeeJeeMVC\Controller;

use BeeJeeMVC\Lib\TaskManager;
use BeeJeeMVC\Lib\Template;
use BeeJeeMVC\Lib\TemplateBuilder;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
     * @var TemplateBuilder
     */
    private $templateBuilder;

    /**
     * @param TaskManager $taskManager
     * @param Request $request
     * @param Template $template
     * @param TemplateBuilder $templateBuilder
     */
    public function __construct(TaskManager $taskManager, Request $request, Template $template, TemplateBuilder $templateBuilder)
    {
        $this->request = $request;
        $this->taskManager = $taskManager;
        $this->template = $template;
        $this->templateBuilder = $templateBuilder;
    }

    /**
     * @return Response
     */
    public function list(): Response
    {
        $page = $this->request->get('page', 1);
        $sortBy = $this->request->get('sortBy');
        $orderBy = $this->request->get('orderBy');

        $content = $this->templateBuilder->buildList($page, $sortBy, $orderBy);

        return new Response($this->template->render('list', ['content' => $content]));
    }

    /**
     * @return Response
     */
    public function create(): ?Response
    {
        if ('POST' !== $this->request->getMethod()) {
            return new Response($this->template->render('form_create'));
        }

        if ($this->request->getSession()->get('admin')) {
            return new Response($this->template->render('form_create', ['error' => self::NOT_ENOUGH_RIGHTS_MSG]));
        }

        $userName = $this->request->request->filter('userName', null, FILTER_SANITIZE_SPECIAL_CHARS);
        $email = $this->request->request->filter('email', null, FILTER_SANITIZE_SPECIAL_CHARS);
        $text = $this->request->request->filter('text', null, FILTER_SANITIZE_SPECIAL_CHARS);

        try {
            $this->taskManager->save($userName, $email, $text);

            return new Response($this->template->render('created'));
        } catch (InvalidArgumentException $exception) {
            return new Response($this->template->render('form_create', ['error' => $exception->getMessage()]));
        }
    }

    /**
     * @return Response
     */
    public function edit(): Response
    {
        if ('POST' !== $this->request->getMethod()) {
            return new Response($this->template->render('form_edit', ['hash' => func_get_args()[0]]));
        }

        if (!$this->request->getSession()->get('admin')) {
            return new Response($this->template->render('edit_error', ['error' => self::NOT_ENOUGH_RIGHTS_MSG]));
        }

        $id = $this->request->request->filter('id', null, FILTER_SANITIZE_SPECIAL_CHARS);
        $text = $this->request->request->filter('text', null, FILTER_SANITIZE_SPECIAL_CHARS);

        try {
            $this->taskManager->edit($id, $text);

            return new Response($this->template->render('edited'));
        } catch (InvalidArgumentException $exception) {
            return new Response($this->template->render('edit_error', ['error' => $exception->getMessage()]));
        }
    }

    /**
     * @return Response
     */
    public function done(): Response
    {
        if (!$this->request->getSession()->get('admin')) {
            return new Response($this->template->render('done_error', ['error' => self::NOT_ENOUGH_RIGHTS_MSG]));
        }

        try {
            $this->taskManager->done(func_get_args()[0]);

            return new Response($this->template->render('done'));
        } catch (InvalidArgumentException $exception) {
            return new Response($this->template->render('done_error', ['error' => $exception->getMessage()]));
        }
    }
}
