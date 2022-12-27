<?php

declare(strict_types=1);

namespace CleverAge\SyliusDpdPlugin\Contract;

interface DpdShippingMethodInterface
{
    public function setDpdPickup(bool $dpdPickup): void;

    public function isDpdPickup(): bool;
}
