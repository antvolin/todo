<?php

namespace Tests\Lib\Service\Token;

use Todo\Lib\App;
use Todo\Lib\Service\Token\TokenService;
use PHPUnit\Framework\TestCase;

class TokenServiceTest extends TestCase
{
    /**
     * @var App
     */
    protected $app;

    /**
     * @var string
     */
    protected $secret;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var string
     */
    protected $tokenSalt;

    /**
     * @var TokenService
     */
    protected $tokenService;

    protected function setUp()
    {
        $this->app = new App();
        $this->tokenService = new TokenService();
        $this->secret = $this->app->getSecret();
        $this->tokenSalt = $this->app->getTokenSalt();
        $this->tokenService->generateToken($this->secret, $this->tokenSalt);
        $this->token = $this->tokenService->getToken();
    }

    /**
     * @test
     */
    public function tokenShouldBeGenerated(): void
    {
        $this->assertEquals($this->tokenSalt.':'.md5($this->tokenSalt.':'.$this->secret), $this->token);
    }

    /**
     * @test
     */
    public function validationOfAValidTokenMustBeSuccessful(): void
    {
        $this->assertTrue($this->tokenService->isValidToken($this->token, $this->secret));
    }

    /**
     * @test
     */
    public function validationOfInvalidTokenMustFail(): void
    {
        $this->assertFalse($this->tokenService->isValidToken('not valid token', 'not valid secret'));
    }
}
