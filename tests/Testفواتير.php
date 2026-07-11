<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Client;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;
use App\Repository\FactureRepository;
use App\Entity\Facture;
use App\Service\FactureService;

class TestFactures extends WebTestCase
{
    private $client;
    private $router;
    private $factureRepository;
    private $factureService;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->router = static::$container->get(RouterInterface::class);
        $this->factureRepository = $this->createMock(FactureRepository::class);
        $this->factureService = $this->createMock(FactureService::class);
    }

    public function testGetFactures()
    {
        $factures = [
            new Facture(),
            new Facture(),
        ];

        $this->factureRepository->expects($this->once())
            ->method('findAll')
            ->willReturn($factures);

        $this->factureService->expects($this->once())
            ->method('getFactures')
            ->willReturn($factures);

        $crawler = $this->client->request('GET', $this->router->generate('factures'));
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('table', 'Factures');
    }

    public function testPostFacture()
    {
        $facture = new Facture();
        $facture->setNom('Facture 1');
        $facture->setMontant(100);

        $this->factureRepository->expects($this->once())
            ->method('save')
            ->with($facture)
            ->willReturn($facture);

        $this->factureService->expects($this->once())
            ->method('createFacture')
            ->with($facture)
            ->willReturn($facture);

        $crawler = $this->client->request('POST', $this->router->generate('factures'), [
            'nom' => 'Facture 1',
            'montant' => 100,
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertSelectorTextContains('h1', 'Facture créée avec succès');
    }

    public function testPutFacture()
    {
        $facture = new Facture();
        $facture->setId(1);
        $facture->setNom('Facture 1');
        $facture->setMontant(100);

        $this->factureRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($facture);

        $this->factureRepository->expects($this->once())
            ->method('save')
            ->with($facture)
            ->willReturn($facture);

        $this->factureService->expects($this->once())
            ->method('updateFacture')
            ->with($facture)
            ->willReturn($facture);

        $crawler = $this->client->request('PUT', $this->router->generate('factures', ['id' => 1]), [
            'nom' => 'Facture 1',
            'montant' => 100,
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains('h1', 'Facture mise à jour avec succès');
    }

    public function testDeleteFacture()
    {
        $facture = new Facture();
        $facture->setId(1);

        $this->factureRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($facture);

        $this->factureRepository->expects($this->once())
            ->method('remove')
            ->with($facture)
            ->willReturn($facture);

        $this->client->request('DELETE', $this->router->generate('factures', ['id' => 1]));

        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }

    public function testGetFactureNotFound()
    {
        $this->factureRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->expectException(NotFoundHttpException::class);

        $this->client->request('GET', $this->router->generate('factures', ['id' => 1]));
    }
}


This test file covers the following scenarios:

1.  **Get Factures**: Tests the GET request to retrieve all factures.
2.  **Post Facture**: Tests the POST request to create a new facture.
3.  **Put Facture**: Tests the PUT request to update an existing facture.
4.  **Delete Facture**: Tests the DELETE request to delete a facture.
5.  **Get Facture Not Found**: Tests the GET request to retrieve a facture that does not exist.

Each test method uses the `createMock` method to create mock objects for the `FactureRepository` and `FactureService` classes. The `expects` method is used to define the expected behavior of these mock objects.

The `setUp` method is used to create a new client instance and set up the router and mock objects.

The `testGetFactures`, `testPostFacture`, `testPutFacture`, `testDeleteFacture`, and `testGetFactureNotFound` methods test the respective CRUD operations.

The `assertResponseStatusCodeSame` method is used to verify that the response status code matches the expected value.

The `assertSelectorTextContains` method is used to verify that the response contains the expected text.

The `expectException` method is used to verify that the expected exception is thrown when the `Get Facture Not Found` test is executed.