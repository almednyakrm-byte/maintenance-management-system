<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\طلبات الصيانةController;
use App\Repository\طلبات الصيانةRepository;
use App\Entity\طلبات الصيانة;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Tests\TestCase as KernelTestCase;
use PHPUnit\Framework\MockObject\MockObject;

class طلبات الصيانةTest extends TestCase
{
    private $controller;
    private $repository;
    private $entityManager;

    public function setUp(): void
    {
        $this->repository = $this->createMock(طلبات الصيانةRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->entityManager->method('getRepository')->willReturn($this->repository);
        $this->controller = new طلبات الصيانةController($this->entityManager);
    }

    public function testGetAll()
    {
        $this->repository->method('findAll')->willReturn([new طلبات الصيانة()]);
        $response = $this->controller->getAll();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testGetOne()
    {
        $id = 1;
        $this->repository->method('find')->willReturn(new طلبات الصيانة());
        $response = $this->controller->getOne($id);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testCreate()
    {
        $data = ['name' => 'Test'];
        $this->repository->method('save')->willReturn(new طلبات الصيانة());
        $response = $this->controller->create($data);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testUpdate()
    {
        $id = 1;
        $data = ['name' => 'Test'];
        $this->repository->method('find')->willReturn(new طلبات الصيانة());
        $this->repository->method('save')->willReturn(new طلبات الصيانة());
        $response = $this->controller->update($id, $data);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testDelete()
    {
        $id = 1;
        $this->repository->method('find')->willReturn(new طلبات الصيانة());
        $this->repository->method('remove')->willReturn(null);
        $response = $this->controller->delete($id);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}



// App\Controller\طلبات الصيانةController.php
namespace App\Controller;

use App\Repository\طلبات الصيانةRepository;
use App\Entity\طلبات الصيانة;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class طلبات الصيانةController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getAll()
    {
        $repository = $this->entityManager->getRepository(طلبات الصيانة::class);
        $items = $repository->findAll();
        return new Response(json_encode($items), Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    public function getOne($id)
    {
        $repository = $this->entityManager->getRepository(طلبات الصيانة::class);
        $item = $repository->find($id);
        return new Response(json_encode($item), Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    public function create(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $item = new طلبات الصيانة();
        $item->setName($data['name']);
        $repository = $this->entityManager->getRepository(طلبات الصيانة::class);
        $repository->save($item);
        return new Response('', Response::HTTP_CREATED, ['Content-Type' => 'application/json']);
    }

    public function update($id, Request $request)
    {
        $item = $this->entityManager->getRepository(طلبات الصيانة::class)->find($id);
        $data = json_decode($request->getContent(), true);
        $item->setName($data['name']);
        $repository = $this->entityManager->getRepository(طلبات الصيانة::class);
        $repository->save($item);
        return new Response('', Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    public function delete($id)
    {
        $item = $this->entityManager->getRepository(طلبات الصيانة::class)->find($id);
        $repository = $this->entityManager->getRepository(طلبات الصيانة::class);
        $repository->remove($item);
        return new Response('', Response::HTTP_NO_CONTENT);
    }
}



// App\Repository\طلبات الصيانةRepository.php
namespace App\Repository;

use App\Entity\طلبات الصيانة;
use Doctrine\ORM\EntityRepository;

class طلبات الصيانةRepository extends EntityRepository
{
    public function save(طلبات الصيانة $item)
    {
        // Save logic here
    }

    public function remove(طلبات الصيانة $item)
    {
        // Remove logic here
    }
}



// App\Entity\طلبات الصيانة.php
namespace App\Entity;

class طلبات الصيانة
{
    private $id;
    private $name;

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
}