<?php

namespace BeeJeeMVC\Lib\RequestHandler;

use BeeJeeMVC\Lib\Exceptions\CannotBeEmptyException;
use BeeJeeMVC\Lib\Exceptions\ForbiddenStatusException;
use BeeJeeMVC\Lib\Exceptions\NotValidEmailException;
use BeeJeeMVC\Lib\Factory\Paginator\PaginatorFactoryInterface;
use BeeJeeMVC\Lib\Service\EntityService;
use BeeJeeMVC\Lib\Service\PathService;
use Symfony\Component\HttpFoundation\Request;

class PaginatorRequestHandler extends RequestHandler
{
    /**
     * @var PaginatorFactoryInterface
     */
    private $paginatorFactory;

    /**
     * @var EntityService
     */
    private $entityManager;

    /**
     * @param PaginatorFactoryInterface $paginatorFactory
     * @param EntityService $entityManager
     */
    public function __construct(
        PaginatorFactoryInterface $paginatorFactory,
        EntityService $entityManager
    )
    {
        $this->paginatorFactory = $paginatorFactory;
        $this->entityManager = $entityManager;
    }

    /**
     * @param Request $request
     *
     * @throws CannotBeEmptyException
     * @throws ForbiddenStatusException
     * @throws NotValidEmailException
     */
    protected function process(Request $request): void
    {
        $pathParts = PathService::getPathParts($request->getPathInfo());

        if (count($pathParts) === 1 || 'list' === PathService::getFirstPart($request->getPathInfo())) {
            $page = $request->get('page', 1);
            $orderBy = $request->get('orderBy');
            $order = $request->get('order');

            $entities = $this->entityManager->getEntities($page, $orderBy, $order);
            $countRows = $this->entityManager->getCountEntities();

            $paginator = $this->paginatorFactory->create($entities, $countRows, $page);

            $request->request->set('paginator', $paginator);
        }
    }
}
