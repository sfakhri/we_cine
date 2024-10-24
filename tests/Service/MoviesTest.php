<?php

namespace App\Tests\Service;

use App\Service\Movies;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class MoviesTest extends TestCase
{
    public function testGetPopularMovies()
    {
        // Mock du client HTTP
        $httpClient = $this->createMock(HttpClientInterface::class);

        // Mock de la réponse HTTP
        $response = $this->createMock(ResponseInterface::class);
        $response->expects(static::once())
            ->method('getStatusCode')
            ->willReturn(200);

        $response->expects(static::once())
            ->method('toArray')
            ->willReturn(['results' => ['movie1', 'movie2']]);

        // Configuration du client HTTP pour retourner la réponse mockée
        $httpClient->expects(static::once())
            ->method('request')
            ->willReturn($response);

        // Instanciation de l'objet Movies
        $moviesService = $this->getMockForAbstractClass(
            Movies::class,
            ['https://api.example.com', 'test_bearer_token', 'test_api_key', $httpClient]
        );

        // Appel de la méthode à tester
        $result = $moviesService->getPopularMovies();

        // Assertions
        static::assertIsArray($result);
        static::assertCount(2, $result);
        static::assertEquals(['movie1', 'movie2'], $result);
    }

    public function testGetMoviesByCategory()
    {
        // Mock du client HTTP
        $httpClient = $this->createMock(HttpClientInterface::class);

        // Mock de la réponse HTTP
        $response = $this->createMock(ResponseInterface::class);
        $response->expects(static::once())
            ->method('getStatusCode')
            ->willReturn(200);

        $response->expects(static::once())
            ->method('toArray')
            ->willReturn(['results' => ['movie1', 'movie2']]);

        // Configuration du client HTTP pour retourner la réponse mockée
        $httpClient->expects(static::once())
            ->method('request')
            ->willReturn($response);

        // Instanciation de l'objet Movies
        $moviesService = $this->getMockForAbstractClass(
            Movies::class,
            ['https://api.example.com', 'test_bearer_token', 'test_api_key', $httpClient]
        );

        // Appel de la méthode à tester avec des IDs de catégorie
        $result = $moviesService->getMoviesByCategory([28, 35]);

        // Assertions
        static::assertIsArray($result);
        static::assertCount(2, $result);
        static::assertEquals(['movie1', 'movie2'], $result);
    }

    public function testGetMovieDetails()
    {
        // Mock du client HTTP
        $httpClient = $this->createMock(HttpClientInterface::class);

        // Mock de la réponse HTTP
        $response = $this->createMock(ResponseInterface::class);
        $response->expects(static::once())
            ->method('getStatusCode')
            ->willReturn(200);

        $response->expects(static::once())
            ->method('toArray')
            ->willReturn(['id' => 123, 'title' => 'Movie Title', 'videos' => ['trailer1', 'trailer2']]);

        // Configuration du client HTTP pour retourner la réponse mockée
        $httpClient->expects(static::once())
            ->method('request')
            ->willReturn($response);

        // Instanciation de l'objet Movies
        $moviesService = $this->getMockForAbstractClass(
            Movies::class,
            ['https://api.example.com', 'test_bearer_token', 'test_api_key', $httpClient]
        );

        // Appel de la méthode à tester pour les détails d'un film
        $result = $moviesService->getMovieDetails(123);

        // Assertions
        static::assertIsArray($result);
        static::assertArrayHasKey('id', $result);
        static::assertEquals(123, $result['id']);
        static::assertEquals('Movie Title', $result['title']);
    }

    public function testGetMoviesSearch()
    {
        // Mock du client HTTP
        $httpClient = $this->createMock(HttpClientInterface::class);

        // Mock de la réponse HTTP
        $response = $this->createMock(ResponseInterface::class);
        $response->expects(static::once())
            ->method('getStatusCode')
            ->willReturn(200);

        $response->expects(static::once())
            ->method('toArray')
            ->willReturn(['results' => ['movie1', 'movie2']]);

        // Configuration du client HTTP pour retourner la réponse mockée
        $httpClient->expects(static::once())
            ->method('request')
            ->willReturn($response);

        // Instanciation de l'objet Movies
        $moviesService = $this->getMockForAbstractClass(
            Movies::class,
            ['https://api.example.com', 'test_bearer_token', 'test_api_key', $httpClient]
        );

        // Appel de la méthode à tester pour la recherche de films
        $result = $moviesService->getMoviesSearch('Inception');

        // Assertions
        static::assertIsArray($result);
        static::assertCount(2, $result);
        static::assertEquals(['movie1', 'movie2'], $result);
    }
}