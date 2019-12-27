<?php

namespace BeeJeeMVC\Controller;

use BeeJeeMVC\Lib\Paginator;
use BeeJeeMVC\Lib\Sorting;
use BeeJeeMVC\Lib\TaskManager;
use Exception;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

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
     * @var string
     */
    private $token;

    /**
     * @var Paginator
     */
    private $paginator;

    /**
     * @var Sorting
     */
    private $sorting;

    /**
     * @var Environment
     */
    private $template;

    /**
     * @param Request $request
     * @param TaskManager $taskManager
     * @param string $token
     * @param Paginator $paginator
     * @param Sorting $sorting
     * @param Environment $template
     */
    public function __construct(
        Request $request,
        TaskManager $taskManager,
        string $token,
        Paginator $paginator,
        Sorting $sorting,
        Environment $template
    )
    {
        $this->request = $request;
        $this->taskManager = $taskManager;
        $this->token = $token;
        $this->paginator = $paginator;
        $this->sorting = $sorting;
        $this->template = $template;
    }

    /**
     * @return Response
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function list(): Response
    {
        $page = $this->request->get('page', 1);
        $sortBy = $this->request->get('sortBy');
        $orderBy = $this->request->get('orderBy');

        $tasks = $this->taskManager->getList($sortBy, $orderBy);
        $this->paginator->createPager($page, $tasks);

        $params = [
            'isAdmin' => $this->request->getSession()->get('admin', false),
            'isCreated' => $this->request->getSession()->get('isCreated', false),
            'page' => $page,
            'orderBy' => $this->sorting->getNextOrderBy($orderBy),
            'tasks' => $this->paginator->getCurrentPageTasks(),
            'pagination' => $this->paginator->getPagination($sortBy, $orderBy),
        ];

        $this->request->getSession()->remove('isCreated');

        return new Response($this->template->render('list.html.twig', $params));
    }

    /**
     * @return RedirectResponse|Response
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function create()
    {
        if ('POST' !== $this->request->getMethod()) {
            $params = ['token' => $this->token];

            return new Response($this->template->render('form_create.html.twig', $params));
        }

        try {
            $this->taskManager->save($this->request->get('userName'), $this->request->get('email'), $this->request->get('text'));
        } catch (InvalidArgumentException $exception) {
            $msg = $exception->getMessage();
            $params = ['error' => $msg, 'token' => $this->token];

            return new Response($this->template->render('form_create.html.twig', $params));
        }

        $this->request->getSession()->set('isCreated', true);

        return new RedirectResponse('/task/list');
    }

    /**
     * @return RedirectResponse|Response
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function edit()
    {
        if ('POST' !== $this->request->getMethod()) {
            $id = func_get_args()[0];

            $params = [
                'id' => $id,
                'text' => $this->taskManager->getById($id)->getText(),
                'token' => $this->token,
            ];

            return new Response($this->template->render('form_edit.html.twig', $params));
        }

        if (!$this->request->getSession()->get('admin')) {
            return new Response(self::NOT_ENOUGH_RIGHTS_MSG, Response::HTTP_FORBIDDEN);
        }

        try {
            $this->taskManager->edit($this->request->get('id'), $this->request->get('text'));
        } catch (InvalidArgumentException $exception) {
            $params = [
                'error' => $exception->getMessage(),
            ];

            return new Response($this->template->render('edit_error', $params));
        }

        return new RedirectResponse('/task/list');
    }

    /**
     * @return RedirectResponse|Response
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function done()
    {
        if (!$this->request->getSession()->get('admin')) {
            return new Response(self::NOT_ENOUGH_RIGHTS_MSG, Response::HTTP_FORBIDDEN);
        }

        try {
            $this->taskManager->done(func_get_args()[0]);
        } catch (Exception $exception) {
            $params = ['error' => $exception->getMessage()];

            return new Response($this->template->render('done_error.html.twig', $params));
        }

        return new RedirectResponse('/task/list');
    }
}
