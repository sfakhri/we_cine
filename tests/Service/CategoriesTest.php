<?php

namespace App\Tests\Service;

use App\Service\Categories;
use App\Service\Api\CallApi;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class CategoriesTest extends TestCase
{
    public function testGetCategoriesSuccess()
    {
        // Mock de la dépendance HttpClientInterface
        $httpClient = $this->createMock(HttpClientInterface::class);

        // Mock de la réponse HTTP
        $response = $this->createMock(ResponseInterface::class);
        $response->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(200);

        $response->expects($this->once())
            ->method('toArray')
            ->willReturn([
                'genres' => [
                    ['id' => 28, 'name' => 'Action'],
                    ['id' => 16, 'name' => 'Animation'],
                ]
            ]);

        // Configuration du client HTTP pour retourner la réponse mockée
        $httpClient->expects($this->once())
            ->method('request')
            ->willReturn($response);

        // Instanciation de la classe Categories avec le client mocké
        $categoriesService = $this->getMockForAbstractClass(
            Categories::class,
            ['https://example.com', 'test_bearer_token', 'test_api_key', $httpClient]
        );

        // Appel de la méthode à tester
        $result = $categoriesService->getCategories();

        // Assertions
        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        $this->assertEquals('Action', $result[0]['name']);
        $this->assertEquals('Animation', $result[1]['name']);
    }

    public function testGetCategoriesEmpty()
    {
        // Mock de la dépendance HttpClientInterface
        $httpClient = $this->createMock(HttpClientInterface::class);

        // Mock de la réponse HTTP avec un tableau vide
        $response = $this->createMock(ResponseInterface::class);
        $response->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(200);

        $response->expects($this->once())
            ->method('toArray')
            ->willReturn([]);

        // Configuration du client HTTP pour retourner la réponse mockée
        $httpClient->expects($this->once())
            ->method('request')
            ->willReturn($response);

        // Instanciation de la classe Categories avec le client mocké
        $categoriesService = $this->getMockForAbstractClass(
            Categories::class,
            ['https://example.com', 'test_bearer_token', 'test_api_key', $httpClient]
        );

        // Appel de la méthode à tester
        $result = $categoriesService->getCategories();

        // Assertions
        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function testGetCategoriesWithApiError()
    {
        // Mock de la dépendance HttpClientInterface
        $httpClient = $this->createMock(HttpClientInterface::class);

        // Mock de la réponse HTTP avec une erreur (404 par exemple)
        $response = $this->createMock(ResponseInterface::class);
        $response->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(404);

        // Pas besoin d'appeler toArray dans ce cas
        $response->expects($this->never())
            ->method('toArray');

        // Configuration du client HTTP pour retourner la réponse mockée
        $httpClient->expects($this->once())
            ->method('request')
            ->willReturn($response);

        // Instanciation de la classe Categories avec le client mocké
        $categoriesService = $this->getMockForAbstractClass(
            Categories::class,
            ['https://example.com', 'test_bearer_token', 'test_api_key', $httpClient]
        );

        // Appel de la méthode à tester
        $result = $categoriesService->getCategories();

        // Assertions
        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }
}