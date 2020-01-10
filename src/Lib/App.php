<?php

namespace BeeJeeMVC\Lib;

use BeeJeeMVC\Lib\Factory\Manager\EntityManagerFactory;
use BeeJeeMVC\Lib\Factory\Manager\TokenManagerFactory;
use BeeJeeMVC\Lib\Factory\Paginator\PagerfantaPaginatorFactory;
use BeeJeeMVC\Lib\Factory\Paginator\PaginatorFactory;
use BeeJeeMVC\Lib\Factory\Repository\EntityFileRepositoryFactory;
use BeeJeeMVC\Lib\Factory\Repository\EntityPdoRepositoryFactory;
use BeeJeeMVC\Lib\Factory\Repository\EntityRepositoryFactory;
use BeeJeeMVC\Lib\Factory\RequestFactory;
use BeeJeeMVC\Lib\Factory\TemplateFactory;
use BeeJeeMVC\Lib\Manager\EntityManager;
use BeeJeeMVC\Lib\Paginator\PaginatorAdapter;
use BeeJeeMVC\Lib\Repository\EntityRepositoryInterface;
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
        return (new SecretGenerator($_ENV['TOKEN_SECRET_PREFIX'], $_ENV['TOKEN_SECRET']))->generateSecret();
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
        return new TokenManagerFactory($_ENV['TOKEN_SALT']);
    }

    /**
     * @return Environment
     */
    public function getTemplate(): Environment
    {
        return (new TemplateFactory())->create();
    }

    /**
     * @return EntityManager
     *
     * @throws Exceptions\NotAllowedEntityName
     */
    public function getEntityManager(): EntityManager
    {
        $entityManagerFactory = (new EntityManagerFactory($_ENV['ENTITY_FOLDER_NAMESPACE']));

        return $entityManagerFactory->create($_ENV['ENTITY_NAME'], $this->getRepository());
    }

    /**
     * @return EntityRepositoryInterface
     *
     * @throws Exceptions\NotAllowedEntityName
     */
    public function getRepository(): EntityRepositoryInterface
    {
        return $this->getRepositoryFactory()->create($_ENV['ENTITY_PER_PAGE']);
    }

    /**
     * @return EntityRepositoryFactory
     *
     * @throws Exceptions\NotAllowedEntityName
     */
    public function getRepositoryFactory(): EntityRepositoryFactory
    {
        if ('sqlite' === $_ENV['PDO_TYPE']) {
            $factory = new EntityPdoRepositoryFactory(
                $_ENV['ENTITY_NAME'],
                $_ENV['PDO_TYPE'],
                $_ENV['DB_FOLDER_NAME'],
                $_ENV['ENTITY_FOLDER_NAMESPACE']
            );
        } else {
            $factory = new EntityFileRepositoryFactory($_ENV['ENTITY_NAME']);
        }

        return $factory;
    }

    /**
     * @return PaginatorFactory
     */
    public function getPaginatorFactory(): PaginatorFactory
    {
        return new PagerfantaPaginatorFactory(new PaginatorAdapter(), $_ENV['ENTITY_PER_PAGE']);
    }
}
