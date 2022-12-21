<?php

declare(strict_types=1);

namespace BEAR\FastlyModule;

use BEAR\QueryRepository\PurgerInterface;
use BEAR\Resource\ResourceInterface;
use Fastly\Api\PurgeApi;
use PHPUnit\Framework\TestCase;
use Ray\Di\Injector;

use function assert;
use function is_array;

class FastlyCachePurgerModuleTest extends TestCase
{
    public function testModule(): void
    {
        $module = new FastlyPurgeModule('apiKey', 'serviceId', true);
        $injector = new Injector($module, $_ENV['TMP_DIR']);

        $this->assertInstanceOf(PurgeApi::class, $injector->getInstance(PurgeApi::class));
        $this->assertInstanceOf(PurgerInterface::class, $injector->getInstance(FastlyCachePurger::class));
    }

    public function testPurge(): void
    {
        $module = ModuleFactory::getInstance('FakeVendor\HelloWorld');
        $module->override(new FakeFastlyPurgeModule('apiKey', 'serviceId', true));

        $injector =  new Injector($module, $_ENV['TMP_DIR']);
        $resource = $injector->getInstance(ResourceInterface::class);
        assert($resource instanceof ResourceInterface);
        $resource->get('page://self/blog-posting');

        $api = $injector->getInstance(PurgeApi::class);
        assert($api instanceof FakeFastlyPurgeApi);
        $this->assertIsArray($api->logs);

        $this->assertSame(1, $api->logs[0]['fastly_soft_purge']);
        $this->assertSame('serviceId', $api->logs[0]['service_id']);
        assert(is_array($api->logs[0]['purge_response']));
        $this->assertSame('_blog-posting_', $api->logs[0]['purge_response']['surrogate_keys'][0]);
    }
}
