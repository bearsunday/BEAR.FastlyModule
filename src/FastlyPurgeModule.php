<?php

declare(strict_types=1);

namespace BEAR\FastlyModule;

use BEAR\FastlyModule\Attribute\FastlyApi;
use BEAR\FastlyModule\Attribute\ServiceId;
use Fastly\Api\PurgeApi;
use Fastly\Configuration;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Ray\Di\AbstractModule;
use Ray\Di\Scope;

/**
 * Provides Fastly's tag-based cache purge feature and derived bindings.
 *
 * The following bindings are provided:
 *
 * PurgerInterface
 * PurgeApi
 */
final class FastlyPurgeModule extends AbstractModule
{
    public function __construct(
        private string $fastlyApiKey,
        private string $fastlyServiceId,
        AbstractModule|null $module = null,
    ) {
        parent::__construct($module);
    }

    /** {@inheritdoc} */
    protected function configure(): void
    {
        $this->bind(Configuration::class)->annotatedWith(Configuration::class)->toInstance(
            Configuration::getDefaultConfiguration()->setApiToken($this->fastlyApiKey),
        );
        $this->bind(PurgeApi::class)->toConstructor(PurgeApi::class, [
            'config' => Configuration::class,
        ])->in(Scope::SINGLETON);
        $this->bind()->annotatedWith(ServiceId::class)->toInstance($this->fastlyServiceId);
        $this->bind(ClientInterface::class)->annotatedWith(FastlyApi::class)
            ->toConstructor(Client::class, ['config' => 'fastly_http_client_options']);
        $this->bind(FastlyCachePurgerInterface::class)->to(FastlyCachePurger::class);
    }
}
