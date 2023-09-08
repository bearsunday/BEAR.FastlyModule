<?php

namespace BEAR\FastlyModule;

use Fastly\Api\PurgeApi;
use Fastly\Configuration;
use Ray\Di\AbstractModule;
use Ray\Di\Scope;

final class FakeFastlyPurgeModule extends AbstractModule
{
    public function __construct(?AbstractModule $module = null)
    {
        parent::__construct($module);
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this->bind(PurgeApi::class)->toConstructor(FakeFastlyPurgeApi::class, [
            'config' => Configuration::class,
        ])->in(Scope::SINGLETON);
    }
}
