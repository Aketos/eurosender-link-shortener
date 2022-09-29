<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Repository\Connector\ConnectorInterface;
use App\Repository\Connector\ElasticsearchConnector;
use App\Repository\LinkRepository;
use App\Repository\TrackerRepository;
use App\Tests\Traits\EntityTestHelperTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class RedirectLinkActionTest extends WebTestCase
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

        $this->mockConnector->method('get')->willReturn($this->createLinkEntityData(1));

        static::getContainer()->set(LinkRepository::class, $linkRepository);
        static::getContainer()->set(ElasticsearchConnector::class, $this->createMock(ElasticsearchConnector::class));
    }

    public function testItRedirectsTheLink(): void
    {
        $this->client->request(
            Request::METHOD_GET,
            '/test'
        );

        $response = $this->client->getResponse();

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(Response::HTTP_FOUND, $response->getStatusCode());

        self::assertEquals('link1', $response->getTargetUrl());
    }
}