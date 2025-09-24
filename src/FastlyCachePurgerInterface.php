<?php

declare(strict_types=1);

namespace BEAR\FastlyModule;

interface FastlyCachePurgerInterface
{
    public function __invoke(string $tag): void;
}
