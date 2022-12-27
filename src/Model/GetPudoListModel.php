<?php

declare(strict_types=1);

namespace CleverAge\SyliusDpdPlugin\Model;

use DateTime;

final class GetPudoListModel extends AbstractModel
{
    private string $address = '';
    private string $zipCode = '';
    private string $city = '';
    private string $countryCode = '';
    private string $dateFrom = '';
    private ?int $maxPudoNumber = null;
    private ?int $maxPudoDistance = null;
    private ?int $weight = null;
    private string $category = '';
    private ?bool $holidayTolerant = null;

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @param string $address
     * @return GetPudoListModel
     */
    public function setAddress(string $address): GetPudoListModel
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return string
     */
    public function getZipCode(): string
    {
        return $this->zipCode;
    }

    /**
     * @param string $zipCode
     * @return GetPudoListModel
     */
    public function setZipCode(string $zipCode): GetPudoListModel
    {
        $this->zipCode = $zipCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return GetPudoListModel
     */
    public function setCity(string $city): GetPudoListModel
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    /**
     * @param string $countryCode
     * @return GetPudoListModel
     */
    public function setCountryCode(string $countryCode): GetPudoListModel
    {
        $this->countryCode = $countryCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getDateFrom(): string
    {
        return $this->dateFrom;
    }

    /**
     * @param string $dateFrom
     * @return GetPudoListModel
     */
    public function setDateFrom(string $dateFrom): GetPudoListModel
    {
        $this->dateFrom = $dateFrom;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getMaxPudoNumber(): ?int
    {
        return $this->maxPudoNumber;
    }

    /**
     * @param int|null $maxPudoNumber
     * @return GetPudoListModel
     */
    public function setMaxPudoNumber(?int $maxPudoNumber): GetPudoListModel
    {
        $this->maxPudoNumber = $maxPudoNumber;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getMaxPudoDistance(): ?int
    {
        return $this->maxPudoDistance;
    }

    /**
     * @param int|null $maxPudoDistance
     * @return GetPudoListModel
     */
    public function setMaxPudoDistance(?int $maxPudoDistance): GetPudoListModel
    {
        $this->maxPudoDistance = $maxPudoDistance;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getWeight(): ?int
    {
        return $this->weight;
    }

    /**
     * @param int|null $weight
     * @return GetPudoListModel
     */
    public function setWeight(?int $weight): GetPudoListModel
    {
        $this->weight = $weight;
        return $this;
    }

    /**
     * @return string
     */
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * @param string $category
     * @return GetPudoListModel
     */
    public function setCategory(string $category): GetPudoListModel
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getHolidayTolerant(): ?bool
    {
        return $this->holidayTolerant;
    }

    /**
     * @param bool|null $holidayTolerant
     * @return GetPudoListModel
     */
    public function setHolidayTolerant(?bool $holidayTolerant): GetPudoListModel
    {
        $this->holidayTolerant = $holidayTolerant;
        return $this;
    }

    public function toArray(): array
    {
        return [
            'requestID' => uniqid('dpd_getpudolist'),
            'address' => $this->address,
            'zipCode' => $this->zipCode,
            'city' => $this->city,
            'countryCode' => $this->countryCode,
            'date_from' => $this->dateFrom ?: (new DateTime())->format('d/m/Y'),
            'max_pudo_number' => (string)($this->maxPudoNumber ?? ''),
            'max_distance_search' => (string)($this->maxPudoDistance ?? ''),
            'weight' => (string)($this->weight ?? ''),
            'category' => $this->category ?: '',
            'holiday_tolerant' => (string)($this->holidayTolerant ?? ''),
        ];
    }
}
