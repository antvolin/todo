<?php

namespace BeeJeeMVC\Lib;

use BeeJeeMVC\Lib\Factory\Paginator\PagerfantaPaginatorFactory;
use BeeJeeMVC\Lib\Factory\Paginator\PaginatorFactory;
use BeeJeeMVC\Lib\Factory\Repository\TaskFileRepositoryFactory;
use BeeJeeMVC\Lib\Factory\Repository\TaskPdoRepositoryFactory;
use BeeJeeMVC\Lib\Factory\Repository\TaskRepositoryFactory;
use BeeJeeMVC\Lib\Factory\RequestFactory;
use BeeJeeMVC\Lib\Factory\TemplateFactory;
use BeeJeeMVC\Lib\Factory\TokenManagerFactory;
use BeeJeeMVC\Lib\Paginator\PaginatorAdapter;
use BeeJeeMVC\Lib\Repository\TaskRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;

class App
{
    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return (new RequestFactory())->create();
    }

    /**
     * @return string
     */
    public function getSecret(): string
    {
        return (new SecretGenerator())->generateSecret();
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->getTokenManagerFactory()->create($this->getRequest())->getToken();
    }

    /**
     * @return TokenManagerFactory
     */
    public function getTokenManagerFactory(): TokenManagerFactory
    {
        return new TokenManagerFactory();
    }

    /**
     * @return Environment
     */
    public function getTemplate(): Environment
    {
        return (new TemplateFactory())->create();
    }

    /**
     * @return TaskManager
     */
    public function getTaskManager(): TaskManager
    {
        return new TaskManager($this->getRepository());
    }

    /**
     * @return TaskRepositoryInterface
     */
    public function getRepository(): TaskRepositoryInterface
    {
        return $this->getRepositoryFactory()->create();
    }

    /**
     * @return TaskRepositoryFactory
     */
    public function getRepositoryFactory(): TaskRepositoryFactory
    {
        if ('sqlite' === $_ENV['REPOSITORY']) {
            $factory = new TaskPdoRepositoryFactory();
        } else {
            $factory = new TaskFileRepositoryFactory();
        }

        return $factory;
    }

    /**
     * @return PaginatorFactory
     */
    public function getPaginatorFactory(): PaginatorFactory
    {
        return new PagerfantaPaginatorFactory(new PaginatorAdapter());
    }
}
