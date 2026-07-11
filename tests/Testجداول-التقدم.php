<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Response;
use Zend\Diactoros\Stream;
use PDO;
use PDOStatement;

class Testجداول_التقدم extends TestCase
{
    private $pdo;
    private $statement;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->statement = $this->createMock(PDOStatement::class);
        $this->pdo->method('prepare')->willReturn($this->statement);
    }

    public function testGetRequest(): void
    {
        $request = new ServerRequest(['REQUEST_METHOD' => 'GET'], [], null, [], [], ['REQUEST_URI' => '/جداول_التقدم']);
        $response = new Response();
        $handler = new RequestHandler($this->pdo);
        $response = $handler->handle($request);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testPostRequest(): void
    {
        $data = ['name' => 'Test', 'description' => 'Test Description'];
        $request = new ServerRequest(
            ['REQUEST_METHOD' => 'POST'],
            [],
            null,
            ['json' => $data],
            [],
            ['REQUEST_URI' => '/جداول_التقدم']
        );
        $response = new Response();
        $handler = new RequestHandler($this->pdo);
        $response = $handler->handle($request);
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testPutRequest(): void
    {
        $data = ['name' => 'Test', 'description' => 'Test Description'];
        $request = new ServerRequest(
            ['REQUEST_METHOD' => 'PUT'],
            [],
            null,
            ['json' => $data],
            [],
            ['REQUEST_URI' => '/جداول_التقدم/1']
        );
        $response = new Response();
        $handler = new RequestHandler($this->pdo);
        $response = $handler->handle($request);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDeleteRequest(): void
    {
        $request = new ServerRequest(['REQUEST_METHOD' => 'DELETE'], [], null, [], [], ['REQUEST_URI' => '/جداول_التقدم/1']);
        $response = new Response();
        $handler = new RequestHandler($this->pdo);
        $response = $handler->handle($request);
        $this->assertEquals(204, $response->getStatusCode());
    }
}

class RequestHandler implements RequestHandlerInterface
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        switch ($request->getMethod()) {
            case 'GET':
                return $this->handleGet($request);
            case 'POST':
                return $this->handlePost($request);
            case 'PUT':
                return $this->handlePut($request);
            case 'DELETE':
                return $this->handleDelete($request);
            default:
                return new Response(405);
        }
    }

    private function handleGet(ServerRequestInterface $request): ResponseInterface
    {
        $statement = $this->pdo->prepare('SELECT * FROM جداول_التقدم');
        $statement->execute();
        $data = $statement->fetchAll();
        $response = new Response();
        $response->getBody()->write(json_encode($data));
        return $response;
    }

    private function handlePost(ServerRequestInterface $request): ResponseInterface
    {
        $data = $request->getParsedBody();
        $statement = $this->pdo->prepare('INSERT INTO جداول_التقدم (name, description) VALUES (:name, :description)');
        $statement->bindParam(':name', $data['name']);
        $statement->bindParam(':description', $data['description']);
        $statement->execute();
        return new Response(201);
    }

    private function handlePut(ServerRequestInterface $request): ResponseInterface
    {
        $id = (int) explode('/', $request->getUri()->getPath())[2];
        $data = $request->getParsedBody();
        $statement = $this->pdo->prepare('UPDATE جداول_التقدم SET name = :name, description = :description WHERE id = :id');
        $statement->bindParam(':id', $id);
        $statement->bindParam(':name', $data['name']);
        $statement->bindParam(':description', $data['description']);
        $statement->execute();
        return new Response();
    }

    private function handleDelete(ServerRequestInterface $request): ResponseInterface
    {
        $id = (int) explode('/', $request->getUri()->getPath())[2];
        $statement = $this->pdo->prepare('DELETE FROM جداول_التقدم WHERE id = :id');
        $statement->bindParam(':id', $id);
        $statement->execute();
        return new Response(204);
    }
}