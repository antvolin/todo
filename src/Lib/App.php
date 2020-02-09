<?php

namespace Todo\Lib;

use PDO;
use Redis;
use Symfony\Component\HttpFoundation\Request;
use Todo\Lib\Factory\Entity\EntityFactory;
use Todo\Lib\Factory\Entity\EntityFactoryInterface;
use Todo\Lib\Factory\Repository\EntityRedisRepositoryFactory;
use Todo\Lib\Factory\Request\RequestFactory;
use Todo\Lib\Factory\Service\EntityServiceFactory;
use Todo\Lib\Factory\Service\PdoServiceFactory;
use Todo\Lib\Factory\Service\RedisDBServiceFactory;
use Todo\Lib\Factory\Service\TokenServiceFactory;
use Todo\Lib\Factory\Paginator\PagerfantaPaginatorServiceFactory;
use Todo\Lib\Factory\Paginator\PaginatorFactoryInterface;
use Todo\Lib\Factory\Repository\EntityFileRepositoryFactory;
use Todo\Lib\Factory\Repository\EntityPdoRepositoryFactory;
use Todo\Lib\Factory\Template\TemplateAdapterInterface;
use Todo\Lib\Factory\Template\TwigTemplateFactory;
use Todo\Lib\Service\Auth\AuthService;
use Todo\Lib\Service\DB\PdoDBService;
use Todo\Lib\Service\DB\RedisDBService;
use Todo\Lib\Service\Entity\EntityServiceInterface;
use Todo\Lib\Service\Paginator\PaginatorAdapter;
use Todo\Lib\Service\Secret\SecretGeneratorService;
use Todo\Lib\Repository\EntityRepositoryInterface;

class App
{
    private ?PDO $pdo = null;
    private ?Redis $redis = null;
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
    private static string $redisHost;
    private static string $redisPort;
    private static string $redisPassword;

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
        self::$redisHost = $_ENV['REDIS_HOST'];
        self::$redisPort = $_ENV['REDIS_PORT'];
        self::$redisPassword = $_ENV['REDIS_PASSWORD'];
    }

    public static function getRedisHost(): string
    {
        return self::$redisHost;
    }

    public static function getRedisPort(): string
    {
        return self::$redisPort;
    }

    public static function getRedisPassword(): string
    {
        return self::$redisPassword;
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
        $tokenServiceFactory = $this->createTokenServiceFactory();
        $tokenServiceFactory->setRequest($this->getRequest());

        return $tokenServiceFactory->createService()->getToken();
    }

    public function createTokenServiceFactory(): TokenServiceFactory
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

        return $entityServiceFactory->createService();
    }

    /**
     * @return EntityRepositoryInterface
     *
     * @throws Exceptions\CannotCreateDirectoryException
     * @throws Exceptions\PdoConnectionException
     * @throws Exceptions\RedisConnectionException
     */
    public function createRepository(): EntityRepositoryInterface
    {
        $entityFactory = $this->createEntityFactory();

        if ('redis' === self::getRepositoryType()) {
            $service = $this->createRedisDBService();

            if (!$this->redis) {
                $this->redis = $service->getDBInstance();
            }

            $factory = new EntityRedisRepositoryFactory(
                $this->redis,
                $entityFactory,
                self::getEntityPerPage()
            );
        } elseif ('pdo' === self::getRepositoryType()) {
            $service = $this->createPdoDBService();

            if (!$this->pdo) {
                $this->pdo = $service->getDBInstance();
            }

            $service->createTables();

            $factory = new EntityPdoRepositoryFactory(
                $this->pdo,
                $entityFactory,
                self::getEntityPerPage(),
                self::getEntityName()
            );
        } else {
            $factory = new EntityFileRepositoryFactory(
                self::getEntityPerPage(),
                self::getEntityName()
            );
        }

        return $factory->createRepository();
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

    private function createPdoDBService(): PdoDBService
    {
        return (new PdoServiceFactory(
                self::getEntityName(),
                self::getDbType(),
                self::getDbFolderName()
            ))->createService();
    }

    private function createRedisDBService(): RedisDBService
    {
        return (new RedisDBServiceFactory(
                self::getRedisHost(),
                self::getRedisPort(),
                self::getRedisPassword(),
            ))->createService();
    }
}
