<?php

declare(strict_types=1);

namespace CleverAge\SyliusDpdPlugin\Factory\Model;

use CleverAge\SyliusDpdPlugin\Model\GetPudoListModel;

final class GetPudoListModelFactory implements ModelFactoryInterface
{
    public function create(): GetPudoListModel
    {
        return new GetPudoListModel();
    }

    public function createFromArray(array $data): GetPudoListModel
    {
        $model = $this->create();

        if (isset($data['address'])) {
            $model->setAddress(substr($data['address'], 0, 59));
        }

        if (isset($data['zipCode'])) {
            $model->setZipCode($data['zipCode']);
        }

        if (isset($data['city'])) {
            $model->setCity($data['city']);
        }

        if (isset($data['countryCode'])) {
            $model->setCountryCode($data['countryCode']);
        }

        if (isset($data['dateFrom'])) {
            $model->setDateFrom($data['dateFrom']);
        }

        if (isset($data['maxPudoNumber'])) {
            $model->setMaxPudoNumber($data['maxPudoNumber']);
        }

        if (isset($data['maxPudoDistance'])) {
            $model->setMaxPudoDistance($data['maxPudoDistance']);
        }

        if (isset($data['weight'])) {
            $model->setWeight($data['weight']);
        }

        if (isset($data['category'])) {
            $model->setCategory($data['category']);
        }

        if (isset($data['holidayTolerant'])) {
            $model->setHolidayTolerant($data['holidayTolerant']);
        }

        return $model;
    }
}
