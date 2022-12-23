<?php

declare(strict_types=1);

namespace BEAR\FastlyModule;

use BEAR\FastlyModule\Attribute\ServiceId;
use BEAR\FastlyModule\Attribute\SoftPurge;
use BEAR\QueryRepository\PurgerInterface;
use Fastly\Api\PurgeApi;
use Fastly\ApiException;

use function explode;

final class FastlyCachePurger implements PurgerInterface
{
    /** @SuppressWarnings("PHPMD.BooleanArgumentFlag") */
    public function __construct(
        private PurgeApi $purgeApi,
        #[ServiceId] private string $fastlyServiceId,
        #[SoftPurge] private bool $enableSoftPurge = false,
    ) {
    }

    /**
     * @throws ApiException
     *
     * @see https://developer.fastly.com/reference/api/purging/
     */
    public function __invoke(string $tag): void
    {
        $this->purgeApi->bulkPurgeTag([
            'fastly_soft_purge' => (int) $this->enableSoftPurge,
            'service_id' => $this->fastlyServiceId,
            'purge_response' => ['surrogate_keys' => explode(' ', $tag)],
        ]);
    }
}
