<?php

namespace BEAR\FastlyModule;

use Fastly\Api\PurgeApi;

final class FakeFastlyPurgeApi extends PurgeApi
{
    /**
     * @var array<array{fastly_soft_purge: bool, service_id: string, purge_response: array{surrogate_keys: array<string>}}>
     */
    public array $logs = [];

    /**
     * @param array<string,mixed> $options
     * @return array<string,string>
     */
    public function bulkPurgeTag($options)
    {
        $this->logs[] = $options;

        return [];
    }
}
