<?php

namespace BEAR\FastlyModule;

use BEAR\QueryRepository\PurgerInterface;
use Fastly\Api\PurgeApi;
use Fastly\Configuration;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Ray\Di\AbstractModule;
use Ray\Di\Scope;

final class FakeFastlyPurgeModule extends AbstractModule
{
    private string $fastlyApiKey;
    private string $fastlyServiceId;
    private bool $enableSoftPurge;

    /**
     * @SuppressWarnings("PHPMD.BooleanArgumentFlag")
     */
    public function __construct(
        string $fastlyApiKey,
        string $fastlyServiceId,
        bool $enableSoftPurge = true,
        ?AbstractModule $module = null
    ) {
        $this->fastlyApiKey = $fastlyApiKey;
        $this->fastlyServiceId = $fastlyServiceId;
        $this->enableSoftPurge = $enableSoftPurge;

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
        $this->bind()->annotatedWith(SoftPurge::class)->toInstance($this->enableSoftPurge);
        $this->bind(ClientInterface::class)->annotatedWith(FastlyApi::class)->to(Client::class);
        $this->bind(PurgerInterface::class)->to(FastlyCachePurger::class);
    }
}
