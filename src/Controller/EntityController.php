<?php

namespace BeeJeeMVC\Controller;

use BeeJeeMVC\Lib\Exceptions\CannotBeEmptyException;
use BeeJeeMVC\Lib\Exceptions\CannotEditEntityException;
use BeeJeeMVC\Lib\Exceptions\ForbiddenStatusException;
use BeeJeeMVC\Lib\Exceptions\PdoErrorsException;
use BeeJeeMVC\Lib\Exceptions\NotValidEmailException;
use BeeJeeMVC\Lib\Exceptions\NotFoundException;
use BeeJeeMVC\Lib\Manager\EntityManager;
use BeeJeeMVC\Lib\Manager\OrderingManager;
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
     * @param Request $request
     * @param EntityManager $entityManager
     * @param Environment $template
     */
    public function __construct(
        Request $request,
        EntityManager $entityManager,
        Environment $template
    )
    {
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
            'order' => OrderingManager::getNextOrder($order),
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
        $token = $this->request->get('token');

        if ('POST' !== $this->request->getMethod()) {
            return new Response($this->template->render('form_create.html.twig', ['token' => $token]));
        }

        try {
            $this->entityManager->saveEntity($this->request->get('user_name'), $this->request->get('email'), $this->request->get('text'));
        } catch (PdoErrorsException $exception) {
            return new Response($this->template->render('form_create.html.twig', ['error' => $exception->getMessage(), 'token' => $token]));
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
     * @throws PdoErrorsException
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
            $text = $this->entityManager->getEntityById($id)->getText();
            $token = $this->request->get('token');

            return new Response($this->template->render('form_edit.html.twig', ['id' => $id, 'text' => $text, 'token' => $token]));
        }

        try {
            $this->entityManager->editEntity($this->request->get('id'), $this->request->get('text'));
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
            $this->entityManager->doneEntity(func_get_args()[0]);
        } catch (Exception $exception) {
            return new Response($this->template->render('done_error.html.twig', ['error' => $exception->getMessage()]));
        }

        return new RedirectResponse('/entity/list');
    }
}
