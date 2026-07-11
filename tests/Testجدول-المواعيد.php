<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Controller\ جدولالمواعيدController;
use App\Repository\ جدولالمواعيدRepository;
use App\Entity\ جدولالمواعيد;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\QueryException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class Testجدولالمواعيد extends TestCase
{
    private $controller;
    private $repository;
    private $entityManager;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock('PDO');
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->repository = $this->createMock(جدولالمواعيدRepository::class);
        $this->controller = new جدولالمواعيدController($this->repository);

        $this->pdo->expects($this->any())
            ->method('prepare')
            ->willReturn($this->createMock('PDOStatement'));

        $this->entityManager->expects($this->any())
            ->method('getRepository')
            ->with(جدولالمواعيد::class)
            ->willReturn($this->repository);

        $this->repository->expects($this->any())
            ->method('findAll')
            ->willReturn([]);

        $this->repository->expects($this->any())
            ->method('find')
            ->willReturn(null);

        $this->repository->expects($this->any())
            ->method('save')
            ->willReturn(new جدولالمواعيد());

        $this->repository->expects($this->any())
            ->method('remove')
            ->willReturn(null);
    }

    public function testGetAll(): void
    {
        $request = new Request();
        $request->setMethod('GET');

        $response = $this->controller->getAll($request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testGetOne(): void
    {
        $request = new Request();
        $request->setMethod('GET');

        $response = $this->controller->getOne($request, 1);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testCreate(): void
    {
        $request = new Request();
        $request->setMethod('POST');
        $request->request->set('name', 'جدول المواعيد');

        $response = $this->controller->create($request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testUpdate(): void
    {
        $request = new Request();
        $request->setMethod('PUT');
        $request->request->set('name', 'جدول المواعيد');

        $response = $this->controller->update($request, 1);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testDelete(): void
    {
        $request = new Request();
        $request->setMethod('DELETE');

        $response = $this->controller->delete($request, 1);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}


Note: This code assumes that the `جدولالمواعيدController` class has methods `getAll`, `getOne`, `create`, `update`, and `delete` which handle the respective CRUD operations. The `جدولالمواعيدRepository` class is also assumed to have methods `findAll`, `find`, `save`, and `remove` which handle the database operations. The `جدولالمواعيد` entity is assumed to have a constructor that takes no arguments.