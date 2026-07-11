<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\OrdersController;
use App\Repository\OrdersRepository;
use App\Entity\Order;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TestOrdersController extends TestCase
{
    private $ordersController;
    private $ordersRepository;
    private $pdo;

    protected function setUp(): void
    {
        $this->ordersRepository = $this->createMock(OrdersRepository::class);
        $this->pdo = $this->createMock(\PDO::class);
        $this->ordersController = new OrdersController($this->ordersRepository);
    }

    public function testGetOrders(): void
    {
        $this->ordersRepository->expects($this->once())
            ->method('findAll')
            ->willReturn([new Order()]);

        $request = new Request();
        $response = $this->ordersController->getOrders($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetOrder(): void
    {
        $order = new Order();
        $this->ordersRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($order);

        $request = new Request();
        $response = $this->ordersController->getOrder($request, 1);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetOrderNotFound(): void
    {
        $this->ordersRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $request = new Request();
        $this->expectException(NotFoundHttpException::class);
        $this->ordersController->getOrder($request, 1);
    }

    public function testCreateOrder(): void
    {
        $order = new Order();
        $this->ordersRepository->expects($this->once())
            ->method('save')
            ->with($order)
            ->willReturn($order);

        $request = new Request();
        $request->request->set('name', 'John Doe');
        $request->request->set('email', 'john.doe@example.com');

        $response = $this->ordersController->createOrder($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdateOrder(): void
    {
        $order = new Order();
        $this->ordersRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($order);

        $request = new Request();
        $request->request->set('name', 'John Doe');
        $request->request->set('email', 'john.doe@example.com');

        $response = $this->ordersController->updateOrder($request, 1);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testUpdateOrderNotFound(): void
    {
        $this->ordersRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $request = new Request();
        $this->expectException(NotFoundHttpException::class);
        $this->ordersController->updateOrder($request, 1);
    }

    public function testDeleteOrder(): void
    {
        $order = new Order();
        $this->ordersRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($order);

        $request = new Request();
        $response = $this->ordersController->deleteOrder($request, 1);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteOrderNotFound(): void
    {
        $this->ordersRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $request = new Request();
        $this->expectException(NotFoundHttpException::class);
        $this->ordersController->deleteOrder($request, 1);
    }
}