<?php

namespace BeeJeeMVC\Lib\RequestHandler;

use BeeJeeMVC\Lib\Exceptions\CannotBeEmptyException;
use BeeJeeMVC\Lib\Exceptions\ForbiddenStatusException;
use BeeJeeMVC\Lib\Exceptions\NotValidEmailException;
use BeeJeeMVC\Lib\Factory\Paginator\PaginatorFactoryInterface;
use BeeJeeMVC\Lib\Manager\EntityManager;
use BeeJeeMVC\Lib\Manager\PathManager;
use Symfony\Component\HttpFoundation\Request;

class PaginatorRequestHandler extends RequestHandler
{
    /**
     * @var PaginatorFactoryInterface
     */
    private $paginatorFactory;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @param PaginatorFactoryInterface $paginatorFactory
     * @param EntityManager $entityManager
     */
    public function __construct(
        PaginatorFactoryInterface $paginatorFactory,
        EntityManager $entityManager
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
        $pathParts = PathManager::getPathParts($request->getPathInfo());

        if (count($pathParts) === 1 || 'list' === strtolower($pathParts[1])) {
            $page = $request->get('page', 1);
            $orderBy = $request->get('orderBy');
            $order = $request->get('order');

            $entities = $this->entityManager->getList($page, $orderBy, $order);
            $countRows = $this->entityManager->getCountRows();

            $paginator = $this->paginatorFactory->create($entities, $countRows, $page);

            $request->request->set('paginator', $paginator);
        }
    }
}
