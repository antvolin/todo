<?php

namespace Todo\Controller;

use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Todo\Lib\Factory\Template\TemplateAdapterInterface;
use Todo\Lib\Service\Entity\EntityServiceInterface;
use Todo\Lib\Service\Ordering\EntityOrderingService;

class EntityController implements ControllerInterface
{
    private Request $request;
    private EntityServiceInterface $entityService;
    private TemplateAdapterInterface $template;

    public function __construct(
        Request $request,
        EntityServiceInterface $entityService,
        TemplateAdapterInterface $template
    )
    {
        $this->request = $request;
        $this->entityService = $entityService;
        $this->template = $template;
    }

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
            'order' => (new EntityOrderingService)->getNextOrder($order),
            'entities' => $paginator->getCurrentPageResults(),
            'pagination' => $paginator->getHtml($orderBy, $order),
        ];

        $this->request->getSession()->remove('isCreated');

         return new Response($this->template->render('list.html.twig', $params));
//        return new JsonResponse($params);
    }

    /**
     * @return RedirectResponse|Response
     */
    public function create()
    {
        $token = $this->request->get('token');

        if ('POST' !== $this->request->getMethod()) {
            return new Response($this->template->render('form_create.html.twig', ['token' => $token]));
        }

        $id = $this->entityService->add($this->request->get('user_name'), $this->request->get('email'), $this->request->get('text'));

        $this->request->request->set('entity_id', $id);
        $this->request->getSession()->set('isCreated', true);

        return new RedirectResponse('/entity/list');
    }

    /**
     * @return RedirectResponse|Response
     */
    public function edit()
    {
        if ('POST' !== $this->request->getMethod()) {
            $id = func_get_args()[0];
            $text = $this->entityService->getById($id)->getText();
            $token = $this->request->get('token');
            $params = ['id' => $id, 'text' => $text, 'token' => $token];

            return new Response($this->template->render('form_edit.html.twig', $params));
        }

        try {
            $this->entityService->edit($this->request->get('id'), $this->request->get('text'));
        } catch (Exception $exception) {
            $params = ['error' => $exception->getMessage()];

            return new Response($this->template->render('edit_error.html.twig', $params));
        }

        return new RedirectResponse('/entity/list');
    }

    /**
     * @return RedirectResponse|Response
     */
    public function done()
    {
        try {
            $this->entityService->done(func_get_args()[0]);
        } catch (Exception $exception) {
            return new Response($this->template->render('done_error.html.twig', ['error' => $exception->getMessage()]));
        }

        return new RedirectResponse('/entity/list');
    }
}
