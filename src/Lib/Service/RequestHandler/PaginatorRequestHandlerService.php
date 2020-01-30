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
    private PaginatorFactoryInterface $paginatorFactory;
    private EntityServiceInterface $entityService;

    /**
     * @param RequestHandlerService|null $nextHandler
     * @param PaginatorFactoryInterface $paginatorFactory
     * @param EntityServiceInterface $entityService
     */
    public function __construct(
        ?RequestHandlerService $nextHandler,
        PaginatorFactoryInterface $paginatorFactory,
        EntityServiceInterface $entityService
    )
    {
        parent::__construct($nextHandler);

        $this->paginatorFactory = $paginatorFactory;
        $this->entityService = $entityService;
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

            $entities = $this->entityService->getCollection($page, $orderBy, $order);
            $countRows = $this->entityService->getCount();

            $paginator = $this->paginatorFactory->create($entities, $countRows, $page);

            $request->request->set('paginator', $paginator);
        }
    }
}
