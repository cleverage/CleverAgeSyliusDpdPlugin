<?php

declare(strict_types=1);

namespace CleverAge\SyliusDpdPlugin\Factory\Model;

use CleverAge\SyliusDpdPlugin\Model\OpeningHourModel;
use CleverAge\SyliusDpdPlugin\Model\PudoItemModel;
use SimpleXMLElement;

final class PudoItemModelFactory implements ModelFactoryInterface
{
    public function create(): PudoItemModel
    {
        return new PudoItemModel();
    }

    public function createFromXml(SimpleXMLElement $item): PudoItemModel
    {
        $model = $this->create();

        $item = json_decode(json_encode($item, JSON_THROW_ON_ERROR), false, 512, JSON_THROW_ON_ERROR);

        $model
            ->setPudoId($item->PUDO_ID)
            ->setName($item->NAME)
            ->setAddress($item->ADDRESS1)
            ->setZipCode($item->ZIPCODE)
            ->setCity($item->CITY)
            ->setLongitude($item->LONGITUDE)
            ->setLatitude($item->LATITUDE);

        if (isset($item->DISTANCE)) {
            $model->setDistance($item->DISTANCE);
        }

        if (isset($item->AVAILABLE)) {
            $model->setAvailable($item->AVAILABLE);
        }

        foreach ($item->OPENING_HOURS_ITEMS->OPENING_HOURS_ITEM as $openingHour) {
            $model->addOpeningHour(
                (new OpeningHourModel())
                    ->setDayId($openingHour->DAY_ID)
                    ->setStartTm($openingHour->START_TM)
                    ->setEndTm($openingHour->END_TM)
            );
        }

        return $model;
    }
}
