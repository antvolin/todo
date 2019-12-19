<?php

namespace BeeJeeMVC\Controller;

use BeeJeeMVC\Lib\TaskManager;
use BeeJeeMVC\Lib\Template;
use BeeJeeMVC\Lib\TemplateBuilder;
use BeeJeeMVC\Lib\TokenManager;
use Exception;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskController
{
    private const NOT_ENOUGH_RIGHTS_MSG = 'Not enough rights for this operation!';
    private const ATTEMPT_TO_USE_CSRF_ATTACK = 'Attempt to use csrf attack!';

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
     * @var TokenManager
     */
    private $tokenManager;

    /**
     * @param TaskManager $taskManager
     * @param Request $request
     * @param Template $template
     * @param TemplateBuilder $templateBuilder
     * @param TokenManager $tokenManager
     */
    public function __construct(
        TaskManager $taskManager,
        Request $request,
        Template $template,
        TemplateBuilder $templateBuilder,
        TokenManager $tokenManager)
    {
        $this->request = $request;
        $this->taskManager = $taskManager;
        $this->template = $template;
        $this->templateBuilder = $templateBuilder;
        $this->tokenManager = $tokenManager;
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
        $args = ['content' => $content];

        $this->request->getSession()->remove('isCreated');

        return new Response($this->template->render('list', $args));
    }

    /**
     * @return RedirectResponse|Response
     */
    public function create()
    {
        if ('POST' !== $this->request->getMethod()) {
            $args = ['token' => $this->tokenManager->getToken()];

            return new Response($this->template->render('form_create', $args));
        }

        if ($this->request->getSession()->get('admin')) {
            return new Response(self::NOT_ENOUGH_RIGHTS_MSG, Response::HTTP_FORBIDDEN);
        }

        if (!$this->tokenManager->checkToken($this->request->get('csrf-token'), $this->request->getSession()->get('secret'))) {
            return new Response(self::ATTEMPT_TO_USE_CSRF_ATTACK, Response::HTTP_FORBIDDEN);
        }

        $userName = $this->request->request->filter('userName', null, FILTER_SANITIZE_SPECIAL_CHARS);
        $email = $this->request->request->filter('email', null, FILTER_SANITIZE_SPECIAL_CHARS);
        $text = $this->request->request->filter('text', null, FILTER_SANITIZE_SPECIAL_CHARS);

        try {
            $this->taskManager->save($userName, $email, $text);
        } catch (InvalidArgumentException $exception) {
            $args = ['error' => $exception->getMessage(), 'token' => $this->tokenManager->getToken()];

            return new Response($this->template->render('form_create', $args));
        }

        $this->request->getSession()->set('isCreated', true);

        return new RedirectResponse('/task/list');
    }

    /**
     * @return RedirectResponse|Response
     */
    public function edit()
    {
        if ('POST' !== $this->request->getMethod()) {
            $args = ['hash' => func_get_args()[0], 'text' => func_get_args()[1], 'token' => $this->tokenManager->getToken()];

            return new Response($this->template->render('form_edit', $args));
        }

        if (!$this->request->getSession()->get('admin')) {
            return new Response(self::NOT_ENOUGH_RIGHTS_MSG, Response::HTTP_FORBIDDEN);
        }

        if (!$this->tokenManager->checkToken($this->request->get('csrf-token'), $this->request->getSession()->get('secret'))) {
            return new Response(self::ATTEMPT_TO_USE_CSRF_ATTACK, Response::HTTP_FORBIDDEN);
        }

        $id = $this->request->request->filter('id', null, FILTER_SANITIZE_SPECIAL_CHARS);
        $text = $this->request->request->filter('text', null, FILTER_SANITIZE_SPECIAL_CHARS);

        try {
            $this->taskManager->edit($id, $text);
        } catch (InvalidArgumentException $exception) {
            $args = ['error' => $exception->getMessage()];

            return new Response($this->template->render('edit_error', $args));
        }

        return new RedirectResponse('/task/list');
    }

    /**
     * @return RedirectResponse|Response
     */
    public function done()
    {
        if (!$this->request->getSession()->get('admin')) {
            return new Response(self::NOT_ENOUGH_RIGHTS_MSG, Response::HTTP_FORBIDDEN);
        }

        try {
            $this->taskManager->done(func_get_args()[0]);
        } catch (Exception $exception) {
            $args = ['error' => $exception->getMessage()];

            return new Response($this->template->render('done_error', $args));
        }

        return new RedirectResponse('/task/list');
    }
}
