<?php

namespace BeeJeeMVC\Lib\Handler;

use BeeJeeMVC\Lib\Exceptions\CannotBeEmptyException;
use BeeJeeMVC\Lib\Exceptions\ForbiddenStatusException;
use BeeJeeMVC\Lib\Exceptions\NotValidEmailException;
use BeeJeeMVC\Lib\Factory\PaginatorFactory;
use BeeJeeMVC\Lib\TaskManager;
use Symfony\Component\HttpFoundation\Request;

class PaginatorRequestHandler extends RequestHandler
{
    /**
     * @var PaginatorFactory
     */
    private $paginatorFactory;

    /**
     * @var TaskManager
     */
    private $taskManager;

    /**
     * @param PaginatorFactory $paginatorFactory
     * @param TaskManager $taskManager
     */
    public function __construct(
        PaginatorFactory $paginatorFactory,
        TaskManager $taskManager
    )
    {
        $this->paginatorFactory = $paginatorFactory;
        $this->taskManager = $taskManager;
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
        // TODO: need encapsulate this logic
        $pathParts = explode('/', ltrim($request->getPathInfo(), '/'));

        if (count($pathParts) === 1 || 'list' === strtolower($pathParts[1])) {
            $page = $request->get('page', 1);
            $orderBy = $request->get('orderBy');
            $order = $request->get('order');

            $tasks = $this->taskManager->getList($page, $orderBy, $order);
            $countRows = $this->taskManager->getCountRows();

            $paginator = $this->paginatorFactory->create($tasks, $countRows, $page);

            $request->request->set('paginator', $paginator);
        }
    }
}
