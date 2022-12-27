<?php

declare(strict_types=1);

namespace CleverAge\SyliusDpdPlugin\Service;

use CleverAge\SyliusDpdPlugin\Exception\GetPudoDetails\MissingGetPudoDetailsSearchArgumentException;
use CleverAge\SyliusDpdPlugin\Factory\Client\DpdHttpClientFactory;
use CleverAge\SyliusDpdPlugin\Factory\Model\PudoItemModelFactory;
use CleverAge\SyliusDpdPlugin\Model\PudoItemModel;
use CleverAge\SyliusDpdPlugin\Validator\DpdParamsValidator;

final class GetPudoDetailsService extends AbstractService
{
    private const ENDPOINT = '/mypudo/mypudo.asmx/GetPudoDetails';
    private PudoItemModelFactory $pudoItemModelFactory;

    public function __construct(
        DpdHttpClientFactory $dpdClient,
        DpdParamsValidator   $validator,
        PudoItemModelFactory $pudoItemModelFactory
    )
    {
        parent::__construct($dpdClient, $validator);
        $this->pudoItemModelFactory = $pudoItemModelFactory;
    }

    private function call(string $pudoId): ?PudoItemModel
    {
        return $this->doCall('GET', self::ENDPOINT, [
            'pudo_id' => $pudoId,
        ]);
    }

    public function byPudoId(string $pudoId): ?PudoItemModel
    {
        return $this->call($pudoId);
    }

    protected function validateDataBeforeCall(array $dataToValidate): void
    {
    }

    /**
     * @throws MissingGetPudoDetailsSearchArgumentException
     * @throws \JsonException
     */
    protected function parseErrorAndThrow(int $errorCode, $response): void
    {
        if (is_string($response)) {
            throw new MissingGetPudoDetailsSearchArgumentException($response);
        }

        throw new MissingGetPudoDetailsSearchArgumentException(json_encode($response, JSON_THROW_ON_ERROR));
    }

    /**
     * @throws \JsonException
     */
    protected function parseResponse($response): ?PudoItemModel
    {
        if (!isset($response->PUDO_ITEMS) || empty($response->PUDO_ITEMS->PUDO_ITEM[0])) {
            return null;
        }

        return $this->pudoItemModelFactory->createFromXml($response->PUDO_ITEMS->PUDO_ITEM[0]);
    }
}
