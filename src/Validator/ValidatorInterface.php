<?php

declare(strict_types=1);

namespace CleverAge\SyliusDpdPlugin\Validator;

interface ValidatorInterface
{
    public function validate($data, array $paramsToValidate): array;
}
