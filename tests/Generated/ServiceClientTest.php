<?php

declare(strict_types=1);

namespace Ondewo\Csi\Tests\Generated;

use Grpc\BaseStub;
use Grpc\ChannelCredentials;
use Ondewo\Csi\Auth\BearerTokenAuthenticator;
use Ondewo\Csi\ConversationsClient;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

/**
 * Constructs the generated service stubs against a dummy target. gRPC channels connect lazily, so
 * nothing here touches the network - but the stub, its channel options and its method surface are
 * all real.
 *
 * PRODUCT-SPECIFIC: the service and method names below come from ondewo-csi-api.
 */
final class ServiceClientTest extends TestCase
{
    private const DUMMY_TARGET = 'localhost:50051';

    /**
     * @var list<BaseStub>
     */
    private array $openClients = [];

    protected function tearDown(): void
    {
        foreach ($this->openClients as $client) {
            $client->close();
        }
        $this->openClients = [];

        parent::tearDown();
    }

    public function testAServiceClientIsConstructedAgainstAnInsecureChannel(): void
    {
        $client = $this->open(new ConversationsClient(self::DUMMY_TARGET, [
            'credentials' => ChannelCredentials::createInsecure(),
        ]));

        self::assertInstanceOf(BaseStub::class, $client);
        // Contains, not equals: gRPC canonicalises the target (`dns:///localhost:50051`) in some
        // core versions.
        self::assertStringContainsString(self::DUMMY_TARGET, $client->getTarget());
    }

    public function testAServiceClientAcceptsTheHandWrittenBearerAuthenticator(): void
    {
        $authenticator = new BearerTokenAuthenticator('a-token');

        // The point of the hand-written auth surface: its output IS a valid `$opts` array for a
        // generated stub. \Grpc\BaseStub rejects a missing `credentials` key and a non-callable
        // `update_metadata`, so constructing successfully proves both.
        $client = $this->open(new ConversationsClient(self::DUMMY_TARGET, $authenticator->channelOptions()));

        self::assertStringContainsString(self::DUMMY_TARGET, $client->getTarget());
    }

    #[DataProvider('unaryMethods')]
    public function testTheExpectedUnaryMethodsExist(string $method): void
    {
        self::assertTrue(
            method_exists(ConversationsClient::class, $method),
            ConversationsClient::class . '::' . $method . '() is missing from the generated stub'
        );

        $reflected = new ReflectionMethod(ConversationsClient::class, $method);
        self::assertTrue($reflected->isPublic());
        // <request message>, array $metadata = [], array $options = []
        self::assertSame(3, $reflected->getNumberOfParameters());
        self::assertSame(1, $reflected->getNumberOfRequiredParameters());
    }

    /**
     * The unary half of `ondewo.csi.Conversations`. The two streaming RPCs of the same service
     * (`S2sStream`, `GetControlStream`) are asserted separately below.
     *
     * @return iterable<string, array{string}>
     */
    public static function unaryMethods(): iterable
    {
        foreach ([
            'CreateS2sPipeline',
            'GetS2sPipeline',
            'UpdateS2sPipeline',
            'DeleteS2sPipeline',
            'ListS2sPipelines',
            'CheckUpstreamHealth',
            'SetControlStatus',
        ] as $method) {
            yield $method => [$method];
        }
    }

    public function testABidirectionalStreamingMethodIsGenerated(): void
    {
        self::assertTrue(method_exists(ConversationsClient::class, 'S2sStream'));

        $reflected = new ReflectionMethod(ConversationsClient::class, 'S2sStream');
        // A bidi stream takes no request message - only $metadata and $options.
        self::assertSame(0, $reflected->getNumberOfRequiredParameters());
    }

    public function testAServerStreamingMethodIsGenerated(): void
    {
        self::assertTrue(method_exists(ConversationsClient::class, 'GetControlStream'));

        $reflected = new ReflectionMethod(ConversationsClient::class, 'GetControlStream');
        // A server stream still takes its single request message, unlike the bidi one above.
        self::assertSame(1, $reflected->getNumberOfRequiredParameters());
    }

    public function testTheGeneratedMethodSurfaceIsNotEmpty(): void
    {
        $methods = get_class_methods(ConversationsClient::class);

        self::assertContains('CreateS2sPipeline', $methods);
        // 9 RPCs + the constructor + the 5 public methods inherited from \Grpc\BaseStub.
        self::assertGreaterThanOrEqual(
            15,
            count($methods),
            'ConversationsClient exposes suspiciously few methods - the service proto may not have been compiled'
        );
    }

    private function open(BaseStub $client): BaseStub
    {
        $this->openClients[] = $client;

        return $client;
    }
}
