<?php

namespace CleverAge\SyliusDpdPlugin\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait DpdShippingMethodTrait
{
    /**
     * @ORM\Column(type="boolean", name="dpd_pickup", options={"default": "0"})
     */
    protected bool $dpdPickup = false;

    public function setDpdPickup(bool $dpdPickup): void
    {
        $this->dpdPickup = $dpdPickup;
    }

    public function isDpdPickup(): bool
    {
        return $this->dpdPickup;
    }
}
