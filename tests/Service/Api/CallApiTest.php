<?php
// tests/Service/Api/CallApiTest.php
namespace App\Tests\Service\Api;

use App\Service\Api\CallApi;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use PHPUnit\Framework\TestCase;

class CallApiTest extends TestCase
{
    private $httpClient;
    private $callApi;

    protected function setUp(): void
    {
// Mock le client HTTP
        $this->httpClient = $this->createMock(HttpClientInterface::class);

// Initialiser l'instance de CallApi avec le mock
        $this->callApi = new class('http://api.example.com', 'fakeBearer', 'fakeApiKey', $this->httpClient) extends CallApi {
        };
    }

    public function testGenerateReturnsArrayOnSuccess()
    {
// Créer un mock pour la réponse
        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->method('getStatusCode')->willReturn(200);
        $responseMock->method('toArray')->willReturn(['data' => 'value']);

// Configurer le client HTTP pour renvoyer le mock de réponse
        $this->httpClient->method('request')->willReturn($responseMock);

// Appeler la méthode generate
        $result = $this->callApi->generate(['queryParam' => 'value'], '/partial');

// Assertions
        $this->assertEquals(['data' => 'value'], $result);
    }

    public function testGenerateReturnsEmptyArrayOnError()
    {
// Créer un mock pour la réponse
        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->method('getStatusCode')->willReturn(404);

// Configurer le client HTTP pour renvoyer le mock de réponse
        $this->httpClient->method('request')->willReturn($responseMock);

// Appeler la méthode generate
        $result = $this->callApi->generate([], '/partial');

// Assertions
        $this->assertEquals([], $result);
    }
}
