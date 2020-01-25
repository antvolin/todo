<?php

namespace Todo\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Todo\Lib\Exceptions\CannotBeEmptyException;
use Todo\Lib\Exceptions\CannotEditEntityException;
use Todo\Lib\Exceptions\ForbiddenStatusException;
use Todo\Lib\Exceptions\PdoErrorsException;
use Todo\Lib\Exceptions\NotValidEmailException;
use Todo\Lib\Exceptions\NotFoundException;
use Exception;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Todo\Lib\Service\Entity\EntityService;
use Todo\Lib\Service\Ordering\OrderingService;
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
     * @var EntityService
     */
    private $entityManager;

    /**
     * @var Environment
     */
    private $template;

    /**
     * @param Request $request
     * @param EntityService $entityManager
     * @param Environment $template
     */
    public function __construct(
        Request $request,
        EntityService $entityManager,
        Environment $template
    )
    {
        $this->request = $request;
        $this->entityManager = $entityManager;
        $this->template = $template;
    }

    /**
     * @return JsonResponse
     *
     * @throws LoaderErrorAlias
     * @throws RuntimeErrorAlias
     * @throws SyntaxErrorAlias
     */
    public function list(): JsonResponse
    {
        $page = $this->request->get('page', 1);
        $orderBy = $this->request->get('orderBy');
        $order = $this->request->get('order');
        $paginator = $this->request->get('paginator');

        $params = [
            'isAdmin' => $this->request->getSession()->get('admin', false),
            'isCreated' => $this->request->getSession()->get('isCreated', false),
            'page' => $page,
            'order' => OrderingService::getNextOrder($order),
            'entities' => $paginator->getCurrentPageResults(),
            'pagination' => $paginator->getHtml($orderBy, $order),
        ];

        $this->request->getSession()->remove('isCreated');

        // return new Response($this->template->render('list.html.twig', $params));
        return new JsonResponse($params);
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
            $id = $this->entityManager->saveEntity($this->request->get('user_name'), $this->request->get('email'), $this->request->get('text'));
        } catch (PdoErrorsException $exception) {
            return new Response($this->template->render('form_create.html.twig', ['error' => $exception->getMessage(), 'token' => $token]));
        }

        $this->request->request->set('entity_id', $id);
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

            return new Response($this->template->render('edit_error.html.twig', $params));
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
