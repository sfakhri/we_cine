<?php
// tests/Service/MoviesTest.php
namespace App\Tests\Service;

use App\Service\Movies;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use PHPUnit\Framework\TestCase;

class MoviesTest extends TestCase
{
    private HttpClientInterface $httpClient;
    private Movies $movies;

    protected function setUp(): void
    {
        // Créer un mock pour le client HTTP
        $this->httpClient = $this->createMock(HttpClientInterface::class);

        // Initialiser l'instance de Movies avec le mock
        $this->movies = new Movies('http://api.example.com', 'fakeBearer', 'fakeApiKey', $this->httpClient);
    }

    public function testGetPopularMoviesReturnsResults()
    {
        // Créer un mock pour la réponse
        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->method('getStatusCode')->willReturn(200);
        $responseMock->method('toArray')->willReturn(['results' => [['id' => 1, 'title' => 'Test Movie']]]);

        // Configurer le client HTTP pour renvoyer le mock de réponse
        $this->httpClient->method('request')->willReturn($responseMock);

        // Appeler la méthode getPopularMovies
        $result = $this->movies->getPopularMovies();

        // Assertions
        $this->assertCount(1, $result);
        $this->assertEquals('Test Movie', $result[0]['title']);
    }

    public function testGetPopularMoviesReturnsEmptyArrayOnError()
    {
        // Créer un mock pour la réponse
        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->method('getStatusCode')->willReturn(404);

        // Configurer le client HTTP pour renvoyer le mock de réponse
        $this->httpClient->method('request')->willReturn($responseMock);

        // Appeler la méthode getPopularMovies
        $result = $this->movies->getPopularMovies();

        // Assertions
        $this->assertEquals([], $result);
    }

    public function testGetMoviesByCategoryReturnsResults()
    {
        // Créer un mock pour la réponse
        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->method('getStatusCode')->willReturn(200);
        $responseMock->method('toArray')->willReturn(['results' => [['id' => 1, 'title' => 'Action Movie']]]);

        // Configurer le client HTTP pour renvoyer le mock de réponse
        $this->httpClient->method('request')->willReturn($responseMock);

        // Appeler la méthode getMoviesByCategory
        $result = $this->movies->getMoviesByCategory([28]); // Exemple avec l'ID de catégorie 28

        // Assertions
        $this->assertCount(1, $result);
        $this->assertEquals('Action Movie', $result[0]['title']);
    }

    public function testGetMovieDetailsReturnsDetails()
    {
        // Créer un mock pour la réponse
        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->method('getStatusCode')->willReturn(200);
        $responseMock->method('toArray')->willReturn(['id' => 1, 'title' => 'Detailed Movie']);

        // Configurer le client HTTP pour renvoyer le mock de réponse
        $this->httpClient->method('request')->willReturn($responseMock);

        // Appeler la méthode getMovieDetails
        $result = $this->movies->getMovieDetails(1); // Exemple avec l'ID de film 1

        // Assertions
        $this->assertEquals('Detailed Movie', $result['title']);
    }

    public function testGetMovieDetailsReturnsEmptyArrayOnError()
    {
        // Créer un mock pour la réponse
        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->method('getStatusCode')->willReturn(404);

        // Configurer le client HTTP pour renvoyer le mock de réponse
        $this->httpClient->method('request')->willReturn($responseMock);

        // Appeler la méthode getMovieDetails
        $result = $this->movies->getMovieDetails(1); // Exemple avec l'ID de film 1

        // Assertions
        $this->assertEquals([], $result);
    }

    public function testGetMoviesSearchReturnsResults()
    {
        // Créer un mock pour la réponse
        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->method('getStatusCode')->willReturn(200);
        $responseMock->method('toArray')->willReturn(['results' => [['id' => 1, 'title' => 'Searched Movie']]]);

        // Configurer le client HTTP pour renvoyer le mock de réponse
        $this->httpClient->method('request')->willReturn($responseMock);

        // Appeler la méthode getMoviesSearch
        $result = $this->movies->getMoviesSearch('Test'); // Exemple avec une recherche

        // Assertions
        $this->assertCount(1, $result);
        $this->assertEquals('Searched Movie', $result[0]['title']);
    }

    public function testGetMoviesSearchReturnsEmptyArrayOnError()
    {
        // Créer un mock pour la réponse
        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->method('getStatusCode')->willReturn(404);

        // Configurer le client HTTP pour renvoyer le mock de réponse
        $this->httpClient->method('request')->willReturn($responseMock);

        // Appeler la méthode getMoviesSearch
        $result = $this->movies->getMoviesSearch('Test'); // Exemple avec une recherche

        // Assertions
        $this->assertEquals([], $result);
    }
}
