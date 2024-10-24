<?php

namespace App\Tests\Service;

use App\Service\Configuration;
use App\Service\Api\CallApi;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ConfigurationTest extends TestCase
{
    public function testGenerateConfigurationsSuccess()
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
                'images' => [
                    'base_url' => 'http://example.com/',
                    'secure_base_url' => 'https://example.com/'
                ]
            ]);

        // Configuration du client HTTP pour retourner la réponse mockée
        $httpClient->expects($this->once())
            ->method('request')
            ->willReturn($response);

        // Instanciation de la classe Configuration avec le client mocké
        $configurationService = $this->getMockForAbstractClass(
            Configuration::class,
            ['https://example.com', 'test_bearer_token', 'test_api_key', $httpClient]
        );

        // Appel de la méthode à tester
        $configurationService->generateConfigurations();

        // Assertions sur les setters
        $this->assertEquals('http://example.com/', $configurationService->getBaseUrl());
        $this->assertEquals('https://example.com/', $configurationService->getSecureBaseUrl());
    }

    public function testGenerateConfigurationsEmpty()
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

        // Instanciation de la classe Configuration avec le client mocké
        $configurationService = $this->getMockForAbstractClass(
            Configuration::class,
            ['https://example.com', 'test_bearer_token', 'test_api_key', $httpClient]
        );

        // Appel de la méthode à tester
        $configurationService->generateConfigurations();

        // Assertions sur les setters, car les données sont vides
        $this->assertEmpty($configurationService->getBaseUrl());
        $this->assertEmpty($configurationService->getSecureBaseUrl());
    }

    public function testGenerateConfigurationsWithApiError()
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

        // Instanciation de la classe Configuration avec le client mocké
        $configurationService = $this->getMockForAbstractClass(
            Configuration::class,
            ['https://example.com', 'test_bearer_token', 'test_api_key', $httpClient]
        );

        // Appel de la méthode à tester
        $configurationService->generateConfigurations();

        // Assertions sur les setters, car il y a eu une erreur
        $this->assertEmpty($configurationService->getBaseUrl());
        $this->assertEmpty($configurationService->getSecureBaseUrl());
    }
}