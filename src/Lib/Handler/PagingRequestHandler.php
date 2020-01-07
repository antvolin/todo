<?php

namespace BeeJeeMVC\Lib\Handler;

use BeeJeeMVC\Lib\Exceptions\CannotBeEmptyException;
use BeeJeeMVC\Lib\Exceptions\ForbiddenStatusException;
use BeeJeeMVC\Lib\Exceptions\NotValidEmailException;
use BeeJeeMVC\Lib\Factory\TaskPdoRepositoryFactory;
use BeeJeeMVC\Lib\Paginator\PagerfantaPaginator;
use BeeJeeMVC\Lib\Paginator\PaginatorAdapter;
use BeeJeeMVC\Lib\Paginator\PaginatorInterface;
use BeeJeeMVC\Lib\TaskManager;
use Symfony\Component\HttpFoundation\Request;

class PagingRequestHandler extends RequestHandler
{
    /**
     * @param Request $request
     *
     * @throws CannotBeEmptyException
     * @throws ForbiddenStatusException
     * @throws NotValidEmailException
     */
    protected function process(Request $request): void
    {
        $controllerMethodName = strtolower(explode('/', ltrim($request->getPathInfo(), '/ '))[1]);

        if (!$controllerMethodName || 'list' === $controllerMethodName) {
            $request->request->set('paginator', $this->createPaginator(
                $request->get('page', 1),
                $request->get('orderBy'),
                $request->get('order'))
            );
        }
    }

    /**
     * @param int $page
     * @param string|null $orderBy
     * @param string|null $order
     *
     * @return PaginatorInterface
     *
     * @throws CannotBeEmptyException
     * @throws ForbiddenStatusException
     * @throws NotValidEmailException
     */
    private function createPaginator(int $page, ?string $orderBy, ?string $order): PaginatorInterface
    {
        $taskManager = new TaskManager((new TaskPdoRepositoryFactory())->create());
        $tasks = $taskManager->getList($page, $orderBy, $order);
        $paginatorAdapter = new PaginatorAdapter();
        $paginatorAdapter->setData($tasks);
        $taskCount = $taskManager->getCountRows();
        $paginatorAdapter->setCountRows($taskCount);

        return new PagerfantaPaginator($paginatorAdapter, $page);
    }
}
