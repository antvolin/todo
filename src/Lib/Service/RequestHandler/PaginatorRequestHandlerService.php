<?php

namespace Todo\Lib\Service\RequestHandler;

use Todo\Lib\Exceptions\CannotBeEmptyException;
use Todo\Lib\Exceptions\ForbiddenStatusException;
use Todo\Lib\Exceptions\NotValidEmailException;
use Todo\Lib\Factory\Paginator\PaginatorFactoryInterface;
use Todo\Lib\Service\Entity\EntityServiceInterface;
use Todo\Lib\Service\Path\PathService;
use Symfony\Component\HttpFoundation\Request;

class PaginatorRequestHandlerService extends RequestHandlerService
{
    /**
     * @var PaginatorFactoryInterface
     */
    private $paginatorFactory;

    /**
     * @var EntityServiceInterface
     */
    private $entityService;

    /**
     * @param PaginatorFactoryInterface $paginatorFactory
     * @param EntityServiceInterface $entityManager
     */
    public function __construct(
        PaginatorFactoryInterface $paginatorFactory,
        EntityServiceInterface $entityManager
    )
    {
        $this->paginatorFactory = $paginatorFactory;
        $this->entityService = $entityManager;
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

            $entities = $this->entityService->getEntities($page, $orderBy, $order);
            $countRows = $this->entityService->getCountEntities();

            $paginator = $this->paginatorFactory->create($entities, $countRows, $page);

            $request->request->set('paginator', $paginator);
        }
    }
}