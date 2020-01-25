<?php

namespace Tests\Lib\Service\Auth;

use Todo\Lib\App;
use Todo\Lib\Service\Auth\AuthService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class AuthServiceTest extends TestCase
{
    /**
     * @var App
     */
    protected $app;

    /**
     * @var AuthService
     */
    protected $service;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var string
     */
    protected $user;

    /**
     * @var string
     */
    protected $password;

    protected function setUp()
    {
        $this->app = new App();
        $this->request = $this->app->getRequest();
        $this->user = $this->app->getUser();
        $this->password = $this->app->getPassword();
        $this->service = new AuthService($this->request, $this->user, $this->password);
    }

    /**
     * @test
     */
    public function shouldBeNotLoginIfNotAdmin(): void
    {
        $response = $this->service->login();

        $this->assertNull($this->request->getSession()->get($this->user));
        $this->assertNull($response);
    }

    /**
     * @test
     */
    public function shouldBeLoginIfAdmin(): void
    {
        $this->request->request->set('user', $this->user);
        $this->request->request->set('password', $this->password);

        $response = $this->service->login();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/entity/list', $response->getTargetUrl());
        $this->assertTrue($this->request->getSession()->get($this->user));
    }

    /**
     * @test
     */
    public function shouldBeLogoutIfAdmin(): void
    {
        $this->request->getSession()->set($this->user, true);

        $response = $this->service->logout();

        $this->assertEquals('/entity/list', $response->getTargetUrl());
        $this->assertNull($this->request->getSession()->get($this->user));
    }
}
