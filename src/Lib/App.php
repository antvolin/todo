<?php

namespace Todo\Lib;

use Todo\Lib\Factory\Service\EntityServiceFactory;
use Todo\Lib\Factory\Service\PdoServiceFactory;
use Todo\Lib\Factory\Service\TokenServiceFactory;
use Todo\Lib\Factory\Service\TokenServiceFactoryInterface;
use Todo\Lib\Factory\Paginator\PagerfantaPaginatorFactory;
use Todo\Lib\Factory\Paginator\PaginatorFactoryInterface;
use Todo\Lib\Factory\Repository\EntityFileRepositoryFactory;
use Todo\Lib\Factory\Repository\EntityPdoRepositoryFactory;
use Todo\Lib\Factory\Repository\EntityRepositoryFactory;
use Todo\Lib\Factory\RequestFactory;
use Todo\Lib\Factory\TemplateFactory;
use Todo\Lib\Service\Auth\AuthService;
use Todo\Lib\Service\Entity\EntityServiceInterface;
use Todo\Lib\Service\Paginator\PaginatorAdapter;
use Todo\Lib\Service\Secret\SecretGeneratorService;
use Todo\Lib\Repository\EntityRepositoryInterface;
use PDO;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;

class App
{
    /**
     * @var Request
     */
    private $request;

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

    /**
     * @var string
     */
    private $user;

    /**
     * @var string
     */
    private $password;

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
        $this->user = $_ENV['APP_USER'];
        $this->password = $_ENV['APP_PASSWORD'];
    }

    /**
     * @return string
     */
    public function getEntityName(): string
    {
        return $this->entityName;
    }

    /**
     * @return string
     */
    public function getEntityClassNamespace(): string
    {
        return $this->entityClassNamespace;
    }

    /**
     * @return int
     */
    public function getEntityPerPage(): int
    {
        return $this->entityPerPage;
    }

    /**
     * @return string
     */
    public function getTokenSecretPrefix(): string
    {
        return $this->tokenSecretPrefix;
    }

    /**
     * @return string
     */
    public function getTokenSecret(): string
    {
        return $this->tokenSecret;
    }

    /**
     * @return string
     */
    public function getTokenSalt(): string
    {
        return $this->tokenSalt;
    }

    /**
     * @return string
     */
    public function getDbFolderName(): string
    {
        return $this->dbFolderName;
    }

    /**
     * @return string
     */
    public function getStorageType(): string
    {
        return $this->storageType;
    }

    /**
     * @return string
     */
    public function getUser(): string
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        if (!$this->request) {
            $factory = new RequestFactory();
            $this->request = $factory->create();
        }

        return $this->request;
    }

    /**
     * @return string
     */
    public function getSecret(): string
    {
        $manager = new SecretGeneratorService($this->getTokenSecretPrefix(), $this->getTokenSecret());

        return $manager->generateSecret();
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->getTokenServiceFactory()->create($this->getRequest())->getToken();
    }

    /**
     * @return TokenServiceFactory
     */
    public function getTokenServiceFactory(): TokenServiceFactoryInterface
    {
        return new TokenServiceFactory($this->getTokenSalt());
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
     * @param string|null $entityName
     *
     * @return EntityServiceInterface
     *
     * @throws Exceptions\NotAllowedEntityName
     */
    public function getEntityService(string $entityName = null): EntityServiceInterface
    {
        $entityServiceFactory = new EntityServiceFactory($this->getEntityClassNamespace());

        return $entityServiceFactory->create($entityName ?? $this->getEntityName());
    }

    /**
     * @return EntityRepositoryInterface
     *
     * @throws Exceptions\NotAllowedEntityName
     * @throws Exceptions\PdoConnectionException
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
     * @throws Exceptions\PdoConnectionException
     */
    public function getRepositoryFactory(): EntityRepositoryFactory
    {
        if ('sqlite' === $this->getStorageType()) {
            $factory = new EntityPdoRepositoryFactory(
                $this->getPdo(),
                $this->getEntityService()
            );
        } else {
            $factory = new EntityFileRepositoryFactory($this->getEntityName());
        }

        return $factory;
    }

    /**
     * @return PDO
     *
     * @throws Exceptions\PdoConnectionException
     */
    public function getPdo(): PDO
    {
        $factory = new PdoServiceFactory(
            $this->getEntityName(),
            $this->getStorageType(),
            $this->getDbFolderName()
        );

        $pdoService = $factory->create();
        $pdo = $pdoService->getPdo();
        $pdoService->createTables();

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

    /**
     * @param Request $request
     *
     * @return AuthService
     */
    public function getAuthService(Request $request): AuthService
    {
        return new AuthService($request, $this->getUser(), $this->getPassword());
    }
}
