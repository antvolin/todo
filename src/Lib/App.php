<?php

namespace Todo\Lib;

use Todo\Lib\Factory\Request\RequestFactory;
use Todo\Lib\Factory\Service\EntityServiceFactory;
use Todo\Lib\Factory\Service\PdoServiceFactory;
use Todo\Lib\Factory\Service\TokenServiceFactory;
use Todo\Lib\Factory\Service\TokenServiceFactoryInterface;
use Todo\Lib\Factory\Paginator\PagerfantaPaginatorFactory;
use Todo\Lib\Factory\Paginator\PaginatorFactoryInterface;
use Todo\Lib\Factory\Repository\EntityFileRepositoryFactory;
use Todo\Lib\Factory\Repository\EntityPdoRepositoryFactory;
use Todo\Lib\Factory\Repository\EntityRepositoryFactory;
use Todo\Lib\Factory\Template\TemplateAdapterInterface;
use Todo\Lib\Factory\Template\TemplateFactory;
use Todo\Lib\Service\Auth\AuthService;
use Todo\Lib\Service\Entity\EntityServiceInterface;
use Todo\Lib\Service\Paginator\PaginatorAdapter;
use Todo\Lib\Service\Secret\SecretGeneratorService;
use Todo\Lib\Repository\EntityRepositoryInterface;
use PDO;
use Symfony\Component\HttpFoundation\Request;

class App
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var string
     */
    private static $entityName;

    /**
     * @var int
     */
    private static $entityPerPage;

    /**
     * @var string
     */
    private static $entityClassNamespace;

    /**
     * @var string
     */
    private static $tokenSecretPrefix;

    /**
     * @var string
     */
    private static $tokenSecret;

    /**
     * @var string
     */
    private static $tokenSalt;

    /**
     * @var string
     */
    private static $dbFolderName;

    /**
     * @var string
     */
    private static $storageType;

    /**
     * @var string
     */
    private static $user;

    /**
     * @var string
     */
    private static $password;

    public function __construct()
    {
        self::$entityName = $_ENV['ENTITY_NAME'];
        self::$entityPerPage = $_ENV['ENTITY_PER_PAGE'];
        self::$entityClassNamespace = $_ENV['ENTITY_CLASS_NAMESPACE'];
        self::$tokenSecretPrefix = $_ENV['TOKEN_SECRET_PREFIX'];
        self::$tokenSecret = $_ENV['TOKEN_SECRET'];
        self::$tokenSalt = $_ENV['TOKEN_SALT'];
        self::$dbFolderName = $_ENV['DB_FOLDER_NAME'];
        self::$storageType = $_ENV['STORAGE_TYPE'];
        self::$user = $_ENV['APP_USER'];
        self::$password = $_ENV['APP_PASSWORD'];
    }

    /**
     * @return string
     */
    public static function getEntityName(): string
    {
        return self::$entityName;
    }

    /**
     * @return string
     */
    public static function getEntityClassNamespace(): string
    {
        return self::$entityClassNamespace;
    }

    /**
     * @return int
     */
    public static function getEntityPerPage(): int
    {
        return self::$entityPerPage;
    }

    /**
     * @return string
     */
    public static function getTokenSecretPrefix(): string
    {
        return self::$tokenSecretPrefix;
    }

    /**
     * @return string
     */
    public static function getTokenSecret(): string
    {
        return self::$tokenSecret;
    }

    /**
     * @return string
     */
    public static function getTokenSalt(): string
    {
        return self::$tokenSalt;
    }

    /**
     * @return string
     */
    public static function getDbFolderName(): string
    {
        return self::$dbFolderName;
    }

    /**
     * @return string
     */
    public static function getStorageType(): string
    {
        return self::$storageType;
    }

    /**
     * @return string
     */
    public static function getUser(): string
    {
        return self::$user;
    }

    /**
     * @return string
     */
    public static function getPassword(): string
    {
        return self::$password;
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
        $manager = new SecretGeneratorService(self::getTokenSecretPrefix(), self::getTokenSecret());

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
        return new TokenServiceFactory(self::getTokenSalt());
    }

    /**
     * @return TemplateAdapterInterface
     */
    public function getTemplate(): TemplateAdapterInterface
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
        $entityServiceFactory = new EntityServiceFactory(self::getEntityClassNamespace());

        return $entityServiceFactory->create($entityName ?? self::getEntityName());
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

        return $factory->create(self::getEntityPerPage());
    }

    /**
     * @return EntityRepositoryFactory
     *
     * @throws Exceptions\NotAllowedEntityName
     * @throws Exceptions\PdoConnectionException
     */
    public function getRepositoryFactory(): EntityRepositoryFactory
    {
        if ('sqlite' === self::getStorageType()) {
            $factory = new EntityPdoRepositoryFactory(
                $this->getPdo(),
                $this->getEntityService()
            );
        } else {
            $factory = new EntityFileRepositoryFactory(self::getEntityName());
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
            self::getEntityName(),
            self::getStorageType(),
            self::getDbFolderName()
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

        return new PagerfantaPaginatorFactory($adapter, self::getEntityPerPage());
    }

    /**
     * @param Request $request
     *
     * @return AuthService
     */
    public function getAuthService(Request $request): AuthService
    {
        return new AuthService($request, self::getUser(), self::getPassword());
    }
}
