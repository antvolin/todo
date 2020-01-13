<?php

namespace BeeJeeMVC\Lib;

use BeeJeeMVC\Lib\Factory\Manager\EntityManagerFactory;
use BeeJeeMVC\Lib\Factory\Manager\PdoManagerFactory;
use BeeJeeMVC\Lib\Factory\Manager\TokenManagerFactory;
use BeeJeeMVC\Lib\Factory\Paginator\PagerfantaPaginatorFactory;
use BeeJeeMVC\Lib\Factory\Paginator\PaginatorFactoryInterface;
use BeeJeeMVC\Lib\Factory\Repository\EntityFileRepositoryFactory;
use BeeJeeMVC\Lib\Factory\Repository\EntityPdoRepositoryFactory;
use BeeJeeMVC\Lib\Factory\Repository\EntityRepositoryFactory;
use BeeJeeMVC\Lib\Factory\RequestFactory;
use BeeJeeMVC\Lib\Factory\TemplateFactory;
use BeeJeeMVC\Lib\Manager\EntityManager;
use BeeJeeMVC\Lib\Manager\EntityManagerInterface;
use BeeJeeMVC\Lib\Manager\SecretGeneratorManager;
use BeeJeeMVC\Lib\Paginator\PaginatorAdapter;
use BeeJeeMVC\Lib\Repository\EntityRepositoryInterface;
use PDO;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;

class App
{
    /**
     * @var string
     */
    private $entityName;

    /**
     * @var int
     */
    private $entityPerPage;

    /**
     * @var string
     */
    private $entityClassNamespace;

    /**
     * @var string
     */
    private $tokenSecretPrefix;

    /**
     * @var string
     */
    private $tokenSecret;

    /**
     * @var string
     */
    private $tokenSalt;

    /**
     * @var string
     */
    private $dbFolderName;

    /**
     * @var string
     */
    private $storageType;

    public function __construct()
    {
        $this->entityName = $_ENV['ENTITY_NAME'];
        $this->entityPerPage = $_ENV['ENTITY_PER_PAGE'];
        $this->entityClassNamespace = $_ENV['ENTITY_CLASS_NAMESPACE'];
        $this->tokenSecretPrefix = $_ENV['TOKEN_SECRET_PREFIX'];
        $this->tokenSecret = $_ENV['TOKEN_SECRET'];
        $this->tokenSalt = $_ENV['TOKEN_SALT'];
        $this->dbFolderName = $_ENV['DB_FOLDER_NAME'];
        $this->storageType = $_ENV['STORAGE_TYPE'];
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        $factory = new RequestFactory();

        return $factory->create();
    }

    /**
     * @return string
     */
    public function getSecret(): string
    {
        $manager = new SecretGeneratorManager($this->getTokenSecretPrefix(), $this->getTokenSecret());

        return $manager->generateSecret();
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
        return new TokenManagerFactory($this->getTokenSalt());
    }

    /**
     * @return Environment
     */
    public function getTemplate(): Environment
    {
        $factory = new TemplateFactory();

        return $factory->create();
    }

    /**
     * @return EntityManager
     *
     * @throws Exceptions\NotAllowedEntityName
     */
    public function getEntityManager(): EntityManagerInterface
    {
        $entityManagerFactory = new EntityManagerFactory($this->getEntityClassNamespace());

        return $entityManagerFactory->create($this->getEntityName(), $this->getRepository());
    }

    /**
     * @return EntityRepositoryInterface
     *
     * @throws Exceptions\NotAllowedEntityName
     */
    public function getRepository(): EntityRepositoryInterface
    {
        $factory = $this->getRepositoryFactory();

        return $factory->create($this->getEntityPerPage());
    }

    /**
     * @return EntityRepositoryFactory
     *
     * @throws Exceptions\NotAllowedEntityName
     */
    public function getRepositoryFactory(): EntityRepositoryFactory
    {
        if ('sqlite' === $this->getStorageType()) {
            $factory = new EntityPdoRepositoryFactory(
                $this->getPdo(),
                $this->getEntityName(),
                $this->getEntityClassNamespace()
            );
        } else {
            $factory = new EntityFileRepositoryFactory($this->getEntityName());
        }

        return $factory;
    }

    /**
     * @return PDO
     */
    public function getPdo(): Pdo
    {
        $factory = new PdoManagerFactory(
            $this->getEntityName(),
            $this->getStorageType(),
            $this->getDbFolderName()
        );

        $pdoManager = $factory->create();
        $pdo = $pdoManager->getPdo();
        $pdoManager->createTables();

        return $pdo;
    }

    /**
     * @return PaginatorFactoryInterface
     */
    public function getPaginatorFactory(): PaginatorFactoryInterface
    {
        $adapter = new PaginatorAdapter();

        return new PagerfantaPaginatorFactory($adapter, $this->getEntityPerPage());
    }

    public function getEntityName()
    {
        return $this->entityName;
    }

    public function getEntityClassNamespace()
    {
        return $this->entityClassNamespace;
    }

    public function getEntityPerPage()
    {
        return $this->entityPerPage;
    }

    public function getTokenSecretPrefix()
    {
        return $this->tokenSecretPrefix;
    }

    public function getTokenSecret()
    {
        return $this->tokenSecret;
    }

    public function getTokenSalt()
    {
        return $this->tokenSalt;
    }

    public function getDbFolderName()
    {
        return $this->dbFolderName;
    }

    public function getStorageType()
    {
        return $this->storageType;
    }
}
