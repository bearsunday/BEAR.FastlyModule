<?php

declare(strict_types=1);

namespace BEAR\FastlyModule;

use Fastly\Api\PurgeApi;

interface FastlyCachePurgerInterface
{
    /** @SuppressWarnings("PHPMD.BooleanArgumentFlag") */
    public function __construct(PurgeApi $purgeApi, string $fastlyServiceId, bool $enableSoftPurge = false);

    public function __invoke(string $tag): void;
}
