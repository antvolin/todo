<?php

namespace BeeJeeMVC\Tests\Lib\Manager;

use BeeJeeMVC\Lib\App;
use BeeJeeMVC\Lib\Manager\TokenManager;
use PHPUnit\Framework\TestCase;

class TokenManagerTest extends TestCase
{
    /**
     * @var string
     */
    protected $secret;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var TokenManager
     */
    protected $tokenManager;

    protected function setUp()
    {
        $this->tokenManager = new TokenManager();
        $this->secret = (new App())->getSecret();
        $this->tokenManager->generateToken($this->secret, $_ENV['TOKEN_SALT']);
        $this->token = $this->tokenManager->getToken();
    }

    /**
     * @test
     */
    public function tokenShouldBeGenerated(): void
    {
        $this->assertEquals($_ENV['TOKEN_SALT'].':'.md5($_ENV['TOKEN_SALT'].':'.$this->secret), $this->token);
    }

    /**
     * @test
     */
    public function validationOfAValidTokenMustBeSuccessful(): void
    {
        $this->assertTrue($this->tokenManager->isValidToken($this->token, $this->secret));
    }

    /**
     * @test
     */
    public function validationOfInvalidTokenMustFail(): void
    {
        $this->assertFalse($this->tokenManager->isValidToken('not valid token', 'not valid secret'));
    }
}
