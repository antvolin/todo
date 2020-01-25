<?php

namespace Todo\Lib\Service\RequestHandler;

use Todo\Lib\Exceptions\CannotBeEmptyException;
use Todo\Lib\Exceptions\ForbiddenStatusException;
use Todo\Lib\Exceptions\NotValidEmailException;
use Todo\Lib\Factory\Paginator\PaginatorFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Todo\Lib\Service\Entity\EntityService;
use Todo\Lib\Service\Path\PathService;

class PaginatorRequestHandlerService extends RequestHandlerService
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
