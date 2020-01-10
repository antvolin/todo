<?php

namespace BeeJeeMVC\Controller;

use BeeJeeMVC\Lib\Exceptions\CannotBeEmptyException;
use BeeJeeMVC\Lib\Exceptions\CannotEditEntityException;
use BeeJeeMVC\Lib\Exceptions\ForbiddenStatusException;
use BeeJeeMVC\Lib\Exceptions\NotUniqueFieldsException;
use BeeJeeMVC\Lib\Exceptions\NotValidEmailException;
use BeeJeeMVC\Lib\Exceptions\NotFoundException;
use BeeJeeMVC\Lib\Manager\EntityManager;
use BeeJeeMVC\Lib\Ordering;
use Exception;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Error\LoaderError as LoaderErrorAlias;
use Twig\Error\RuntimeError as RuntimeErrorAlias;
use Twig\Error\SyntaxError as SyntaxErrorAlias;

class EntityController
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
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var Environment
     */
    private $template;

    /**
     * @param string $token
     * @param Request $request
     * @param EntityManager $entityManager
     * @param Environment $template
     */
    public function __construct(
        string $token,
        Request $request,
        EntityManager $entityManager,
        Environment $template
    )
    {
        $this->token = $token;
        $this->request = $request;
        $this->entityManager = $entityManager;
        $this->template = $template;
    }

    /**
     * @return Response
     *
     * @throws LoaderErrorAlias
     * @throws RuntimeErrorAlias
     * @throws SyntaxErrorAlias
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
            'entities' => $paginator->getCurrentPageResults(),
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
     * @throws LoaderErrorAlias
     * @throws RuntimeErrorAlias
     * @throws SyntaxErrorAlias
     */
    public function create()
    {
        if ('POST' !== $this->request->getMethod()) {
            $params = ['token' => $this->token];

            return new Response($this->template->render('form_create.html.twig', $params));
        }

        try {
            $this->entityManager->save($this->request->get('user_name'), $this->request->get('email'), $this->request->get('text'));
        } catch (NotUniqueFieldsException $exception) {
            $msg = $exception->getMessage();
            $params = ['error' => $msg, 'token' => $this->token];

            return new Response($this->template->render('form_create.html.twig', $params));
        }

        $this->request->getSession()->set('isCreated', true);

        return new RedirectResponse('/entity/list');
    }

    /**
     * @return RedirectResponse|Response
     *
     * @throws CannotBeEmptyException
     * @throws CannotEditEntityException
     * @throws ForbiddenStatusException
     * @throws NotUniqueFieldsException
     * @throws NotValidEmailException
     * @throws NotFoundException
     * @throws LoaderErrorAlias
     * @throws RuntimeErrorAlias
     * @throws SyntaxErrorAlias
     */
    public function edit()
    {
        if ('POST' !== $this->request->getMethod()) {
            $id = func_get_args()[0];

            $params = [
                'id' => $id,
                'text' => $this->entityManager->getById($id)->getText(),
                'token' => $this->token,
            ];

            return new Response($this->template->render('form_edit.html.twig', $params));
        }

        try {
            $this->entityManager->edit($this->request->get('id'), $this->request->get('text'));
        } catch (InvalidArgumentException $exception) {
            $params = ['error' => $exception->getMessage()];

            return new Response($this->template->render('edit_error', $params));
        }

        return new RedirectResponse('/entity/list');
    }

    /**
     * @return RedirectResponse|Response
     *
     * @throws LoaderErrorAlias
     * @throws RuntimeErrorAlias
     * @throws SyntaxErrorAlias
     */
    public function done()
    {
        try {
            $this->entityManager->done(func_get_args()[0]);
        } catch (Exception $exception) {
            $params = ['error' => $exception->getMessage()];

            return new Response($this->template->render('done_error.html.twig', $params));
        }

        return new RedirectResponse('/entity/list');
    }
}
