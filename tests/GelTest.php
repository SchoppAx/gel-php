<?php

declare(strict_types=1);

use mehrWEBnet\Gel\Api\Shipments;
use mehrWEBnet\Gel\Api\ShipmentQuotes;
use mehrWEBnet\Gel\Api\Api;
use mehrWEBnet\Gel\Gel;
use mehrWEBnet\Gel\Http\Client;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

final class GelTest extends TestCase
{
    public function testMakeReturnsConfiguredClient(): void
    {
        $gel = Gel::make('test-key', 1, [123], true);

        $this->assertInstanceOf(Gel::class, $gel);
        $this->assertInstanceOf(Shipments::class, $gel->shipments());
        $this->assertInstanceOf(ShipmentQuotes::class, $gel->shipmentQuotes());
    }

    public function testGelRejectsUnknownApi(): void
    {
        $gel = new TestGel('key');

        $this->expectException(BadMethodCallException::class);
        $gel->api('unknown-api');
    }

    public function testApiExecutesRequestsWithEncodedAuthenticationAndParameters(): void
    {
        $client = new RecordingClient(new Response(200, [], '<response><value>ok</value></response>'));
        $api = new TestApi($client);

        $this->assertSame(['value' => 'ok'], $api->get('shipments', ['name' => 'A B', 'collicnt' => 1, 'colli' => ["|5|100|45|6"]], 1));
        $this->assertSame('get', $client->calls[0]['method']);
        $this->assertSame('shipments', $client->calls[0]['uri']);
        $this->assertSame(
            'key=test-key&depot=3&knr=22&name=A%20B&collicnt=1&colli=|5|100|45|6',
            $client->calls[0]['options']['query'],
        );
    }

    public function testApiExecutesRequestsWithEncodedAuthenticationAndMultiParameters(): void
    {
        $client = new RecordingClient(new Response(200, [], '<response><value>ok</value></response>'));
        $api = new TestApi($client);

        $colli = ["|5|100|45|6", "|5|100|45|7"];
        $this->assertSame(['value' => 'ok'], $api->get('shipments', ['name' => 'A B', 'collicnt' => 2, 'colli' => $colli], 1));
        $this->assertSame('get', $client->calls[0]['method']);
        $this->assertSame('shipments', $client->calls[0]['uri']);
        $this->assertSame(
            'key=test-key&depot=3&knr=22&name=A%20B&collicnt=2&colli=|5|100|45|6&colli=|5|100|45|7',
            $client->calls[0]['options']['query'],
        );
    }

    public function testApiPostAndDeleteParseXmlAndHandleInvalidPayloads(): void
    {
        $client = new RecordingClient(new Response(200, [], '<root flag="yes"><item id="1">first</item><item id="2">second</item></root>'));
        $api = new TestApi($client);

        $this->assertSame(
            ['item' => ['id' => '1', 'value' => 'first', ['id' => '2', 'value' => 'second']], '@attributes' => ['flag' => 'yes']],
            $api->post('create'),
        );

        $client->response = new Response(200, [], '');
        $this->assertSame([], $api->delete('remove'));
        $this->assertSame('delete', $client->calls[1]['method']);

        $client->response = new Response(200, [], '<broken>');
        $this->assertSame([], $api->get());
    }

    public function testShipmentOperationsBuildExpectedPayloads(): void
    {
        $client = new RecordingClient(new Response(200, [], '<result><status>ok</status></result>'));
        $shipments = new TestShipments($client);

        $shipments->create(['reference' => 'A']);
        $shipments->modify('S1', ['reference' => 'B']);
        $shipments->remove('S2');
        $shipments->export('S3', 'label');
        $shipments->setstatus('S4', 'status');

        $this->assertSame('key=test-key&depot=3&knr=11&function=create&reference=A', $client->calls[0]['options']['query']);
        $this->assertSame('key=test-key&depot=3&knr=11&function=modify&snr=S1&reference=B', $client->calls[1]['options']['query']);
        $this->assertSame('key=test-key&depot=3&knr=11&function=delete&snr=S2', $client->calls[2]['options']['query']);
        $this->assertSame('key=test-key&depot=3&knr=11&function=export&snr=S3&load=label', $client->calls[3]['options']['query']);
        $this->assertSame('key=test-key&depot=3&knr=11&function=export&snr=S4&load=status', $client->calls[4]['options']['query']);
    }

    public function testShipmentQuoteBuildsCalculatePayload(): void
    {
        $client = new RecordingClient(new Response(200, [], '<result><status>ok</status></result>'));
        $quotes = new TestShipmentQuotes($client);

        $quotes->create(['country' => 'DE'], 1);

        $this->assertSame('key=test-key&depot=3&knr=22&function=calculate&country=DE', $client->calls[0]['options']['query']);
    }

    public function testHttpClientAuthenticatesAndDelegatesToGuzzle(): void
    {
        $client = new Client('key', 4, [10, 20], true);
        $guzzle = $this->createMock(GuzzleClient::class);
        $response = new Response();
        $request = new Request('GET', 'https://example.test');
        $guzzle->expects($this->once())->method('send')->with($request, ['timeout' => 1])->willReturn($response);
        $guzzle->expects($this->once())->method('get')->with('status')->willReturn($response);

        $property = new ReflectionProperty(Client::class, 'httpClient');
        $property->setValue($client, $guzzle);

        $this->assertSame(['key' => 'key', 'depot' => 4, 'knr' => 20], $client->getAuthentication(1));
        $this->assertSame(['key' => 'key', 'depot' => 4, 'knr' => null], $client->getAuthentication(2));
        $this->assertSame($response, $client->send($request, ['timeout' => 1]));
        $this->assertSame($response, $client->get('status'));
    }

    public function testHttpClientAcceptsSingleCustomerNumber(): void
    {
        $client = new Client('key', 4, 10, false);

        $this->assertSame(['key' => 'key', 'depot' => 4, 'knr' => 10], $client->getAuthentication());
    }
}

class TestGel extends Gel
{
    public function api(string $method): Shipments|ShipmentQuotes
    {
        return $this->getApiInstance($method);
    }
}

class RecordingClient extends Client
{
    /** @var array<int, array{method: string, uri: string, options: array}> */
    public array $calls = [];

    public function __construct(public ResponseInterface $response)
    {
    }

    public function getAuthentication(int $pos = 0): array
    {
        return ['key' => 'test-key', 'depot' => 3, 'knr' => [11, 22][$pos] ?? null];
    }

    public function __call(string $method, array $arguments): mixed
    {
        $this->calls[] = ['method' => $method, 'uri' => $arguments[0], 'options' => $arguments[1]];

        return $this->response;
    }
}

class TestApi extends Api
{
    public function __construct(private Client $client)
    {
        parent::__construct('test-key', 3, [11, 22]);
    }

    protected function getClient(): Client
    {
        return $this->client;
    }
}

class TestShipments extends Shipments
{
    public function __construct(private Client $client)
    {
        parent::__construct('test-key', 3, [11, 22]);
    }

    protected function getClient(): Client
    {
        return $this->client;
    }
}

class TestShipmentQuotes extends ShipmentQuotes
{
    public function __construct(private Client $client)
    {
        parent::__construct('test-key', 3, [11, 22]);
    }

    protected function getClient(): Client
    {
        return $this->client;
    }
}
