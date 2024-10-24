<?php

namespace App\Tests\Service\Api;

use App\Service\Api\CallApi;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class CallApiTest extends TestCase
{
    public function testGetWithStatusCodeOk()
    {
        // Mock des dépendances
        $httpClient = $this->createMock(HttpClientInterface::class);

        // Mock de la réponse HTTP
        $response = $this->createMock(ResponseInterface::class);
        $response->expects(static::once())
            ->method('getStatusCode')
            ->willReturn(200);

        $response->expects(static::once())
            ->method('toArray')
            ->willReturn(['data' => 'test data']);

        // Configuration du client HTTP pour retourner la réponse mockée
        $httpClient->expects(static::once())
            ->method('request')
            ->willReturn($response);

        // Instanciation de l'objet CallApi
        $callApi = $this->getMockForAbstractClass(
            CallApi::class,
            ['https://example.com', 'test_bearer_token', 'test_api_key', $httpClient]
        );

        // Appel de la méthode à tester
        $result = $callApi->generate(['param1' => 'value1'], '/endpoint');

        // Assertions
        static::assertIsArray($result);
        static::assertEquals(['data' => 'test data'], $result);
    }

    public function testGetWithStatusCodeKo()
    {
        // Mock des dépendances
        $httpClient = $this->createMock(HttpClientInterface::class);

        // Mock de la réponse HTTP avec un code 404 (erreur)
        $response = $this->createMock(ResponseInterface::class);
        $response->expects(static::once())
            ->method('getStatusCode')
            ->willReturn(404);

        $response->expects(static::never())
            ->method('toArray');

        // Configuration du client HTTP pour retourner la réponse mockée
        $httpClient->expects(static::once())
            ->method('request')
            ->willReturn($response);

        // Instanciation de l'objet CallApi
        $callApi = $this->getMockForAbstractClass(
            CallApi::class,
            ['https://example.com', 'test_bearer_token', 'test_api_key', $httpClient]
        );

        // Appel de la méthode à tester
        $result = $callApi->generate([], '/endpoint');

        // Assertions
        static::assertEmpty($result);
    }
}