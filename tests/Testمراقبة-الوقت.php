<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\Repository\مراقبة_الوقتRepository;
use App\Service\مراقبة_الوقتService;

class Testمراقبة_الوقت extends WebTestCase
{
    private $client;
    private $router;
    private $tokenStorage;
    private $مراقبة_الوقتRepository;
    private $مراقبة_الوقتService;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->router = static::$container->get(RouterInterface::class);
        $this->tokenStorage = static::$container->get(TokenStorageInterface::class);
        $this->مراقبة_الوقتRepository = static::$container->get(مراقبة_الوقتRepository::class);
        $this->مراقبة_الوقتService = static::$container->get(مراقبة_الوقتService::class);
    }

    public function testGetAll()
    {
        $request = Request::create('/مراقبة_الوقت', 'GET');
        $response = $this->client->get($this->router->generate('مراقبة_الوقت_index'));
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }

    public function testGetOne()
    {
        $مراقبة_الوقت = $this->مراقبة_الوقتRepository->findOneBy(['id' => 1]);
        $request = Request::create('/مراقبة_الوقت/' . $مراقبة_الوقت->getId(), 'GET');
        $response = $this->client->get($this->router->generate('مراقبة_الوقت_show', ['id' => $مراقبة_الوقت->getId()]));
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }

    public function testCreate()
    {
        $مراقبة_الوقت = new \stdClass();
        $مراقبة_الوقت->name = 'مراقبة_الوقت';
        $request = Request::create('/مراقبة_الوقت', 'POST', [], json_encode($مراقبة_الوقت));
        $response = $this->client->post($this->router->generate('مراقبة_الوقت_new'), $request->request->all(), 'application/json');
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }

    public function testUpdate()
    {
        $مراقبة_الوقت = $this->مراقبة_الوقتRepository->findOneBy(['id' => 1]);
        $request = Request::create('/مراقبة_الوقت/' . $مراقبة_الوقت->getId(), 'PUT', [], json_encode($مراقبة_الوقت));
        $response = $this->client->put($this->router->generate('مراقبة_الوقت_edit', ['id' => $مراقبة_الوقت->getId()]), $request->request->all(), 'application/json');
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }

    public function testDelete()
    {
        $مراقبة_الوقت = $this->مراقبة_الوقتRepository->findOneBy(['id' => 1]);
        $request = Request::create('/مراقبة_الوقت/' . $مراقبة_الوقت->getId(), 'DELETE');
        $response = $this->client->delete($this->router->generate('مراقبة_الوقت_delete', ['id' => $مراقبة_الوقت->getId()]));
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}


Note: This code assumes you have a `مراقبة_الوقتRepository` and `مراقبة_الوقتService` class in your application, and that you have a `مراقبة_الوقت` entity with a `name` property. You may need to adjust the code to fit your specific application.