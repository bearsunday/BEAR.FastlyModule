<?php

namespace BEAR\FastlyModule;

use BEAR\FastlyModule\Attribute\FastlyApi;
use BEAR\FastlyModule\Attribute\ServiceId;
use BEAR\FastlyModule\Attribute\SoftPurge;
use BEAR\QueryRepository\PurgerInterface;
use Fastly\Api\PurgeApi;
use Fastly\Configuration;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Ray\Di\AbstractModule;
use Ray\Di\Scope;

final class FakeFastlyPurgeModule extends AbstractModule
{
    public function __construct(
        private string $fastlyApiKey,
        private string $fastlyServiceId,
        ?AbstractModule $module = null
    ) {

        parent::__construct($module);
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this->bind(Configuration::class)->annotatedWith(Configuration::class)->toInstance(
            Configuration::getDefaultConfiguration()->setApiToken($this->fastlyApiKey)
        );
        $this->bind(PurgeApi::class)->toConstructor(FakeFastlyPurgeApi::class, [
            'config' => Configuration::class,
        ])->in(Scope::SINGLETON);
        $this->bind()->annotatedWith(ServiceId::class)->toInstance($this->fastlyServiceId);
        $this->bind()->annotatedWith(SoftPurge::class)->toInstance(false);
        $this->bind(ClientInterface::class)->annotatedWith(FastlyApi::class)->to(Client::class);
        $this->bind(PurgerInterface::class)->to(FastlyCachePurger::class);
    }
}
