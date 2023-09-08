<?php

declare(strict_types=1);

namespace BEAR\FastlyModule;

use Fastly\Api\PurgeApi;
use PHPUnit\Framework\TestCase;
use Ray\Di\Injector;

use function assert;

class FastlySoftPurgeModuleTest extends TestCase
{
    public function testSoftPurge(): void
    {
        $module = new FastlyPurgeModule('apiKey', 'serviceId');
        $module->override(new FastlyEnableSoftPurgeModule(new FakeFastlyPurgeModule()));

        $injector =  new Injector($module, $_ENV['TMP_DIR']);
        $cachePurger = $injector->getInstance(FastlyCachePurgerInterface::class);
        assert($cachePurger instanceof FastlyCachePurgerInterface);
        ($cachePurger)('fakeTag');

        $purgeApi = $injector->getInstance(PurgeApi::class);
        assert($purgeApi instanceof FakeFastlyPurgeApi);
        $this->assertSame($purgeApi->logs[0]['fastly_soft_purge'], 1);
    }
}
