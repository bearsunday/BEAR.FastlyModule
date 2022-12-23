<?php

declare(strict_types=1);

namespace BEAR\FastlyModule;

use BEAR\FastlyModule\Attribute\SoftPurge;
use Ray\Di\AbstractModule;

final class FastlyEnableSoftPurgeModule extends AbstractModule
{
    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this->bind()->annotatedWith(SoftPurge::class)->toInstance(true);
    }
}
