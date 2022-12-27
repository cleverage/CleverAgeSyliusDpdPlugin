<?php

namespace CleverAge\SyliusDpdPlugin\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait DpdOrderTrait
{
    /**
     * @ORM\Column(type="string", name="dpd_pickup_point_id", nullable=true)
     */
    protected ?string $dpdPickupPointId = null;

    public function setDpdPickupPointId(?string $id): void
    {
        $this->dpdPickupPointId = $id;
    }

    public function getDpdPickupPointId(): ?string
    {
        return $this->dpdPickupPointId;
    }
}
