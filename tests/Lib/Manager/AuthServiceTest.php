<?php

namespace BeeJeeMVC\Tests\Lib\Manager;

use BeeJeeMVC\Lib\App;
use BeeJeeMVC\Lib\Manager\AuthService;
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

    protected function setUp()
    {
        $this->app = new App();
        $this->request = $this->app->getRequest();
        $this->service = new AuthService($this->request);
    }

    /**
     * @test
     */
    public function shouldBeNotLoginIfNotAdmin(): void
    {
        $response = $this->service->login();

        $this->assertFalse($this->request->getSession()->get('admin'));
        $this->assertNull($response);
    }

    /**
     * @test
     */
    public function shouldBeLoginIfAdmin(): void
    {
        $this->request->request->set('user', 'admin');
        $this->request->request->set('password', $_ENV['PASSWORD']);

        $response = $this->service->login();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('/entity/list', $response->getTargetUrl());
        $this->assertTrue($this->request->getSession()->get('admin'));
    }
}
