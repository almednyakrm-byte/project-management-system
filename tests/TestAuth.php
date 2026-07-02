<?php

namespace App\Tests\Unit\Auth;

use App\Auth\AuthService;
use App\Auth\AuthRepository;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class TestAuth extends TestCase
{
    private $authService;
    private $authRepository;
    private $session;

    protected function setUp(): void
    {
        $this->session = new Session(new MockArraySessionStorage());
        $this->authRepository = $this->createMock(AuthRepository::class);
        $this->authService = new AuthService($this->authRepository, $this->session);
    }

    public function testLoginSuccess()
    {
        $username = 'test_user';
        $password = 'test_password';

        $this->authRepository->expects($this->once())
            ->method('getUserByUsername')
            ->with($username)
            ->willReturn(['id' => 1, 'username' => $username, 'password' => $password]);

        $this->authRepository->expects($this->once())
            ->method('verifyPassword')
            ->with($password)
            ->willReturn(true);

        $this->authService->login($username, $password);

        $this->assertTrue($this->session->has('logged_in'));
        $this->assertEquals(1, $this->session->get('user_id'));
    }

    public function testLoginFailure()
    {
        $username = 'test_user';
        $password = 'test_password';

        $this->authRepository->expects($this->once())
            ->method('getUserByUsername')
            ->with($username)
            ->willReturn(null);

        $this->authService->login($username, $password);

        $this->assertFalse($this->session->has('logged_in'));
    }

    public function testRegisterSuccess()
    {
        $username = 'test_user';
        $password = 'test_password';

        $this->authRepository->expects($this->once())
            ->method('getUserByUsername')
            ->with($username)
            ->willReturn(null);

        $this->authRepository->expects($this->once())
            ->method('registerUser')
            ->with(['username' => $username, 'password' => $password])
            ->willReturn(['id' => 1, 'username' => $username, 'password' => $password]);

        $this->authService->register($username, $password);

        $this->assertTrue($this->session->has('logged_in'));
        $this->assertEquals(1, $this->session->get('user_id'));
    }

    public function testRegisterFailure()
    {
        $username = 'test_user';
        $password = 'test_password';

        $this->authRepository->expects($this->once())
            ->method('getUserByUsername')
            ->with($username)
            ->willReturn(['id' => 1, 'username' => $username, 'password' => $password]);

        $this->authService->register($username, $password);

        $this->assertFalse($this->session->has('logged_in'));
    }
}


This test file covers the following scenarios:

- `testLoginSuccess`: Tests that a user can log in successfully with the correct username and password.
- `testLoginFailure`: Tests that a user cannot log in with an incorrect username or password.
- `testRegisterSuccess`: Tests that a user can register successfully with a new username and password.
- `testRegisterFailure`: Tests that a user cannot register with an existing username.

Each test method uses the `createMock` method to create a mock object for the `AuthRepository` class, which is used to simulate the database interactions. The `expects` method is used to specify the expected behavior of the mock object, and the `willReturn` method is used to specify the return value of the mock object.