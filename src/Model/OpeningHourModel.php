<?php

declare(strict_types=1);

namespace CleverAge\SyliusDpdPlugin\Model;

use JsonSerializable;

final class OpeningHourModel extends AbstractModel implements JsonSerializable
{
    private const DAYS_MAPPING = [
        '1' => 'monday',
        '2' => 'tuesday',
        '3' => 'wednesday',
        '4' => 'thursday',
        '5' => 'friday',
        '6' => 'saturday',
        '7' => 'sunday',
    ];

    private string $dayId = '';
    private string $startTm = '';
    private string $endTm = '';

    public function getDayId(): string
    {
        return $this->dayId;
    }

    public function setDayId(string $dayId): OpeningHourModel
    {
        $this->dayId = $dayId;
        return $this;
    }

    public function getStartTm(): string
    {
        return $this->startTm;
    }

    public function setStartTm(string $startTm): OpeningHourModel
    {
        $this->startTm = $startTm;
        return $this;
    }

    public function getEndTm(): string
    {
        return $this->endTm;
    }

    public function setEndTm(string $endTm): OpeningHourModel
    {
        $this->endTm = $endTm;
        return $this;
    }

    public function getDay(): string
    {
        return self::DAYS_MAPPING[$this->dayId];
    }

    public function jsonSerialize(): array
    {
        return [
            'day' => $this->getDay(),
            'startTm' => $this->getStartTm(),
            'endTm' => $this->getEndTm(),
        ];
    }
}
