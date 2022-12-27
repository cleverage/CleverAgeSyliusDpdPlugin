<?php

declare(strict_types=1);

namespace CleverAge\SyliusDpdPlugin\Model;

use JsonSerializable;

final class PudoItemModel extends AbstractModel implements JsonSerializable
{
    private string $pudoId = '';
    private string $distance = '';
    private string $name = '';
    private string $address = '';
    private string $zipCode = '';
    private string $city = '';
    private string $longitude = '';
    private string $latitude = '';
    private string $available = '';

    /** @var OpeningHourModel[] */
    private array $openingHours = [];


    public function __toString(): string
    {
        return "$this->name $this->address, $this->zipCode, $this->city";
    }

    public function jsonSerialize(): array
    {
        return [
            'pudoId' => $this->pudoId,
            'distance' => $this->distance,
            'name' => $this->name,
            'address' => $this->address,
            'zipCode' => $this->zipCode,
            'city' => $this->city,
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
            'available' => $this->available,
            'openingHours' => $this->getOpeningHoursGroupedByDays(),
        ];
    }

    public function getPudoId(): string
    {
        return $this->pudoId;
    }

    public function setPudoId(string $pudoId): PudoItemModel
    {
        $this->pudoId = $pudoId;
        return $this;
    }

    public function getDistance(): string
    {
        return $this->distance;
    }

    public function setDistance(string $distance): PudoItemModel
    {
        $this->distance = $distance;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): PudoItemModel
    {
        $this->name = $name;
        return $this;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): PudoItemModel
    {
        $this->address = $address;
        return $this;
    }

    public function getZipCode(): string
    {
        return $this->zipCode;
    }

    public function setZipCode(string $zipCode): PudoItemModel
    {
        $this->zipCode = $zipCode;
        return $this;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): PudoItemModel
    {
        $this->city = $city;
        return $this;
    }

    public function getLongitude(): string
    {
        return $this->longitude;
    }

    public function setLongitude(string $longitude): PudoItemModel
    {
        $this->longitude = $longitude;
        return $this;
    }

    public function getLatitude(): string
    {
        return $this->latitude;
    }

    public function setLatitude(string $latitude): PudoItemModel
    {
        $this->latitude = $latitude;
        return $this;
    }

    public function getAvailable(): string
    {
        return $this->available;
    }

    public function setAvailable(string $available): PudoItemModel
    {
        $this->available = $available;
        return $this;
    }

    /**
     * @return OpeningHourModel[]
     */
    public function getOpeningHours(): array
    {
        return $this->openingHours;
    }

    /**
     * @param OpeningHourModel[] $openingHours
     */
    public function setOpeningHours(array $openingHours): PudoItemModel
    {
        $this->openingHours = $openingHours;
        return $this;
    }

    public function addOpeningHour(OpeningHourModel $openingHour): PudoItemModel
    {
        $this->openingHours[] = $openingHour;
        return $this;
    }

    public function getOpeningHoursGroupedByDays(): array
    {
        $groupedOpeningHours = [];
        foreach ($this->openingHours as $openingHour) {
            $groupedOpeningHours[$openingHour->getDay()][] = $openingHour;
        }
        return $groupedOpeningHours;
    }
}
