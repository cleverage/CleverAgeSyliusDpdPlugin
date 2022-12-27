<?php

declare(strict_types=1);

namespace CleverAge\SyliusDpdPlugin\Factory\Model;

use CleverAge\SyliusDpdPlugin\Model\AbstractModel;

interface ModelFactoryInterface
{
    public function create(): AbstractModel;

//    public function createFromArray(array $data): AbstractModel;
}
