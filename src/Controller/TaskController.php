<?php

namespace BeeJeeMVC\Controller;

use BeeJeeMVC\Lib\Exceptions\CannotBeEmptyException;
use BeeJeeMVC\Lib\Exceptions\CannotEditTaskException;
use BeeJeeMVC\Lib\Exceptions\ForbiddenStatusException;
use BeeJeeMVC\Lib\Exceptions\NotUniqueTaskFieldsException;
use BeeJeeMVC\Lib\Exceptions\NotValidEmailException;
use BeeJeeMVC\Lib\Exceptions\TaskNotFoundException;
use BeeJeeMVC\Lib\Ordering;
use BeeJeeMVC\Lib\TaskManager;
use Exception;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class TaskController
{
    /**
     * @var string
     */
    private $token;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var TaskManager
     */
    private $taskManager;

    /**
     * @var Environment
     */
    private $template;

    /**
     * @param string $token
     * @param Request $request
     * @param TaskManager $taskManager
     * @param Environment $template
     */
    public function __construct(
        string $token,
        Request $request,
        TaskManager $taskManager,
        Environment $template
    )
    {
        $this->token = $token;
        $this->request = $request;
        $this->taskManager = $taskManager;
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
        $orderBy = $this->request->get('orderBy');
        $order = $this->request->get('order');
        $paginator = $this->request->get('paginator');

        $params = [
            'isAdmin' => $this->request->getSession()->get('admin', false),
            'isCreated' => $this->request->getSession()->get('isCreated', false),
            'page' => $page,
            'order' => Ordering::getNextOrder($order),
            'tasks' => $paginator->getCurrentPageResults(),
            'pagination' => $paginator->getHtml($orderBy, $order),
        ];

        $this->request->getSession()->remove('isCreated');

        return new Response($this->template->render('list.html.twig', $params));
    }

    /**
     * @return RedirectResponse|Response
     *
     * @throws ForbiddenStatusException
     * @throws CannotBeEmptyException
     * @throws NotValidEmailException
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
            $this->taskManager->save($this->request->get('user_name'), $this->request->get('email'), $this->request->get('text'));
        } catch (NotUniqueTaskFieldsException $exception) {
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
     * @throws CannotBeEmptyException
     * @throws CannotEditTaskException
     * @throws ForbiddenStatusException
     * @throws NotUniqueTaskFieldsException
     * @throws NotValidEmailException
     * @throws TaskNotFoundException
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

        try {
            $this->taskManager->edit($this->request->get('id'), $this->request->get('text'));
        } catch (InvalidArgumentException $exception) {
            $params = ['error' => $exception->getMessage()];

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
        try {
            $this->taskManager->done(func_get_args()[0]);
        } catch (Exception $exception) {
            $params = ['error' => $exception->getMessage()];

            return new Response($this->template->render('done_error.html.twig', $params));
        }

        return new RedirectResponse('/task/list');
    }
}
