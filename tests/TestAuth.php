<?php

namespace App\Tests\Unit\Auth;

use App\Auth\AuthService;
use App\Auth\User;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

class TestAuth extends TestCase
{
    private $authService;
    private $connectionMock;

    protected function setUp(): void
    {
        $this->connectionMock = $this->createMock(Connection::class);
        $this->authService = new AuthService($this->connectionMock);
    }

    public function testLoginSuccess()
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->connectionMock
            ->expects($this->once())
            ->method('executeQuery')
            ->with('SELECT * FROM users WHERE username = ?', [$username])
            ->willReturn([
                ['id' => 1, 'username' => $username, 'password' => $password],
            ]);

        $this->authService->login($username, $password);

        $this->assertTrue($this->authService->isLoggedIn());
    }

    public function testLoginFailure()
    {
        $username = 'testuser';
        $password = 'wrongpassword';

        $this->connectionMock
            ->expects($this->once())
            ->method('executeQuery')
            ->with('SELECT * FROM users WHERE username = ?', [$username])
            ->willReturn([]);

        $this->authService->login($username, $password);

        $this->assertFalse($this->authService->isLoggedIn());
    }

    public function testRegisterSuccess()
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->connectionMock
            ->expects($this->once())
            ->method('insert')
            ->with('users', ['username' => $username, 'password' => $password])
            ->willReturn(1);

        $this->authService->register($username, $password);

        $this->assertTrue($this->authService->isLoggedIn());
    }

    public function testRegisterFailure()
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->connectionMock
            ->expects($this->once())
            ->method('insert')
            ->with('users', ['username' => $username, 'password' => $password])
            ->willThrowException(new Exception('Database error'));

        $this->expectException(Exception::class);
        $this->authService->register($username, $password);
    }
}


This test file covers the following scenarios:

- `testLoginSuccess`: Tests that a user can log in successfully.
- `testLoginFailure`: Tests that a user cannot log in with incorrect credentials.
- `testRegisterSuccess`: Tests that a user can register successfully.
- `testRegisterFailure`: Tests that a user cannot register when there is a database error.

Note that this test file assumes that the `AuthService` class has methods `login`, `register`, and `isLoggedIn`, and that the `User` class is not used in this test file. You may need to adjust the test file to fit your specific use case.