<?php

declare(strict_types=1);

namespace BEAR\FastlyModule;

use Attribute;
use Ray\Di\Di\Qualifier;

/**
 * @Annotation
 * @Target("METHOD")
 * @Qualifier
 * @NamedArgumentConstructor
 */
#[Attribute(Attribute::TARGET_PARAMETER), Qualifier]
final class ServiceId
{
    public function __construct()
    {
    }
}
