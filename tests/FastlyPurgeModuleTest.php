<?php

declare(strict_types=1);

namespace BEAR\FastlyModule;

use Fastly\Api\PurgeApi;
use PHPUnit\Framework\TestCase;
use Ray\Di\Injector;

use function assert;

class FastlyPurgeModuleTest extends TestCase
{
    public function testModule(): void
    {
        $module = new FastlyPurgeModule('apiKey', 'serviceId');
        $injector = new Injector($module, $_ENV['TMP_DIR']);

        $this->assertInstanceOf(PurgeApi::class, $injector->getInstance(PurgeApi::class));
        $this->assertInstanceOf(FastlyCachePurgerInterface::class, $injector->getInstance(FastlyCachePurgerInterface::class));
    }

    public function testPurge(): void
    {
        $module = new FastlyPurgeModule('apiKey', 'serviceId');
        $module->override(new FakeFastlyPurgeModule());

        $injector =  new Injector($module, $_ENV['TMP_DIR']);
        $cachePurger = $injector->getInstance(FastlyCachePurgerInterface::class);
        assert($cachePurger instanceof FastlyCachePurgerInterface);
        ($cachePurger)('fakeTag');

        $purgeApi = $injector->getInstance(PurgeApi::class);
        assert($purgeApi instanceof FakeFastlyPurgeApi);
        $this->assertSame($purgeApi->logs[0]['purge_response']['surrogate_keys'], ['fakeTag']);
        $this->assertSame($purgeApi->logs[0]['fastly_soft_purge'], 0);
    }
}
