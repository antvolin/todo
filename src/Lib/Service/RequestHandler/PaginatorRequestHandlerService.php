<?php

namespace Todo\Lib\Service\RequestHandler;

use Todo\Lib\Factory\Paginator\PaginatorFactoryInterface;
use Todo\Lib\Service\Entity\EntityServiceInterface;
use Todo\Lib\Service\Path\PathService;
use Symfony\Component\HttpFoundation\Request;

class PaginatorRequestHandlerService extends RequestHandlerService
{
    private PaginatorFactoryInterface $paginatorFactory;
    private EntityServiceInterface $entityService;

    public function __construct(
        PaginatorFactoryInterface $paginatorFactory,
        EntityServiceInterface $entityService
    )
    {
        parent::__construct();

        $this->paginatorFactory = $paginatorFactory;
        $this->entityService = $entityService;
    }

    protected function process(Request $request): void
    {
        $pathParts = PathService::separatePath($request->getPathInfo());

        if (count($pathParts) === 1 || 'list' === PathService::getFirstPathPart($request->getPathInfo())) {
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
