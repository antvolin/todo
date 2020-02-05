<?php

namespace Todo\Lib;

use PDO;
use Symfony\Component\HttpFoundation\Request;
use Todo\Lib\Factory\Entity\EntityFactory;
use Todo\Lib\Factory\Entity\EntityFactoryInterface;
use Todo\Lib\Factory\Request\RequestFactory;
use Todo\Lib\Factory\Service\EntityServiceFactory;
use Todo\Lib\Factory\Service\PdoServiceFactory;
use Todo\Lib\Factory\Service\TokenServiceFactory;
use Todo\Lib\Factory\Service\TokenServiceFactoryInterface;
use Todo\Lib\Factory\Paginator\PagerfantaPaginatorServiceFactory;
use Todo\Lib\Factory\Paginator\PaginatorFactoryInterface;
use Todo\Lib\Factory\Repository\EntityFileRepositoryFactory;
use Todo\Lib\Factory\Repository\EntityPdoRepositoryFactory;
use Todo\Lib\Factory\Template\TemplateAdapterInterface;
use Todo\Lib\Factory\Template\TwigTemplateFactory;
use Todo\Lib\Service\Auth\AuthService;
use Todo\Lib\Service\Entity\EntityServiceInterface;
use Todo\Lib\Service\Paginator\PaginatorAdapter;
use Todo\Lib\Service\Pdo\PdoDatabaseService;
use Todo\Lib\Service\Secret\SecretGeneratorService;
use Todo\Lib\Repository\EntityRepositoryInterface;

class App
{
    private ?PDO $pdo = null;
    private Request $request;
    private static int $entityPerPage;
    private static string $entityName;
    private static string $entityClassNamespace;
    private static string $viewType;
    private static string $tokenSecretPrefix;
    private static string $tokenSecret;
    private static string $tokenSalt;
    private static string $dbFolderName;
    private static string $repositoryType;
    private static string $dbType;
    private static string $user;
    private static string $password;

    public function __construct()
    {
        $factory = new RequestFactory();
        $this->request = $factory->create();

        self::$entityName = $_ENV['ENTITY_NAME'];
        self::$entityPerPage = $_ENV['ENTITY_PER_PAGE'];
        self::$entityClassNamespace = $_ENV['ENTITY_CLASS_NAMESPACE'];
        self::$tokenSecretPrefix = $_ENV['TOKEN_SECRET_PREFIX'];
        self::$tokenSecret = $_ENV['TOKEN_SECRET'];
        self::$tokenSalt = $_ENV['TOKEN_SALT'];
        self::$dbFolderName = $_ENV['DB_FOLDER_NAME'];
        self::$repositoryType = $_ENV['REPOSITORY_TYPE'];
        self::$dbType = $_ENV['DB_TYPE'];
        self::$user = $_ENV['APP_USER'];
        self::$password = $_ENV['APP_PASSWORD'];
        self::$viewType = $_ENV['VIEW_TYPE'];
    }

    public static function getEntityName(): string
    {
        return self::$entityName;
    }

    public static function getEntityClassNamespace(): string
    {
        return self::$entityClassNamespace;
    }

    public static function getEntityPerPage(): int
    {
        return self::$entityPerPage;
    }

    public static function getViewType(): string
    {
        return self::$viewType;
    }

    public static function getTokenSecretPrefix(): string
    {
        return self::$tokenSecretPrefix;
    }

    public static function getTokenSecret(): string
    {
        return self::$tokenSecret;
    }

    public static function getTokenSalt(): string
    {
        return self::$tokenSalt;
    }

    public static function getDbFolderName(): string
    {
        return self::$dbFolderName;
    }

    public static function getRepositoryType(): string
    {
        return self::$repositoryType;
    }

    public static function getDbType(): string
    {
        return self::$dbType;
    }

    public static function getUser(): string
    {
        return self::$user;
    }

    public static function getPassword(): string
    {
        return self::$password;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function createSecret(): string
    {
        $service = new SecretGeneratorService(self::getTokenSecretPrefix(), self::getTokenSecret());

        return $service->generateSecret();
    }

    public function createToken(): string
    {
        return $this->createTokenServiceFactory()->create($this->getRequest())->getToken();
    }

    public function createTokenServiceFactory(): TokenServiceFactoryInterface
    {
        return new TokenServiceFactory(self::getTokenSalt());
    }

    public function createTemplate(): TemplateAdapterInterface
    {
        $factory = null;

        if ('twig' === self::getViewType()) {
            $factory = new TwigTemplateFactory();
        }

        return $factory->create();
    }

    public function createEntityService(): EntityServiceInterface
    {
        $entityServiceFactory = new EntityServiceFactory($this->createEntityFactory());

        return $entityServiceFactory->create();
    }

    /**
     * @return EntityRepositoryInterface
     *
     * @throws Exceptions\CannotCreateDirectoryException
     */
    public function createRepository(): EntityRepositoryInterface
    {
        if ('pdo' === self::getRepositoryType()) {
            $this->createPdo();
            $this->createTables();

            $factory = new EntityPdoRepositoryFactory(
                $this->pdo,
                $this->createEntityFactory()
            );
        } else {
            $factory = new EntityFileRepositoryFactory();
        }

        return $factory->create(self::getEntityPerPage(), self::getEntityName());
    }

    public function createEntityFactory(): EntityFactoryInterface
    {
        return new EntityFactory(self::getEntityClassNamespace(), self::getEntityName());
    }

    public function createPaginatorFactory(): PaginatorFactoryInterface
    {
        return new PagerfantaPaginatorServiceFactory(new PaginatorAdapter(), self::getEntityPerPage());
    }

    public function createAuthService(Request $request): AuthService
    {
        return new AuthService($request, self::getUser(), self::getPassword());
    }

    private function createPdo(): PDO
    {
        if (!$this->pdo) {
            $this->pdo = (new PdoServiceFactory(
                self::getEntityName(),
                self::getDbType(),
                self::getDbFolderName()
            ))->create()->getPdo();
        }

        return $this->pdo;
    }

    private function createTables(): void
    {
        if ('pdo' === self::getRepositoryType()) {
            $pdoDatabaseService = new PdoDatabaseService($this->pdo, self::getEntityName());
            $pdoDatabaseService->createTables();
        }
    }
}
