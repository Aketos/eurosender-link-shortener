<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Repository\Connector\ConnectorInterface;
use App\Repository\LinkRepository;
use App\Tests\Traits\EntityTestHelperTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ShortenLinkActionTest extends WebTestCase
{
    use EntityTestHelperTrait;

    private KernelBrowser $client;

    /**
     * @var mixed|\PHPUnit\Framework\MockObject\MockObject
     */
    private $mockConnector;

    public function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();

        $this->mockConnector = $this->createMock(ConnectorInterface::class);

        $linkRepository = new LinkRepository(
            $this->mockConnector,
            static::getContainer()->get(NormalizerInterface::class)
        );

        static::getContainer()->set(LinkRepository::class, $linkRepository);
    }

    public function testItShortensTheLink(): void
    {
        $this->client->request(
            Request::METHOD_GET,
            '/api/v1/shorten?link=http://some.end/point'
        );

        $response = $this->client->getResponse();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        self::assertStringContainsString('test/', json_decode($response->getContent()));
    }
}