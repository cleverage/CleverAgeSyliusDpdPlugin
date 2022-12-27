<?php

declare(strict_types=1);

namespace CleverAge\SyliusDpdPlugin\Service;

use CleverAge\SyliusDpdPlugin\Exception\GetPudoList\MissingGetPudoListSearchArgumentException;
use CleverAge\SyliusDpdPlugin\Factory\Client\DpdHttpClientFactory;
use CleverAge\SyliusDpdPlugin\Factory\Model\GetPudoListModelFactory;
use CleverAge\SyliusDpdPlugin\Factory\Model\PudoItemModelFactory;
use CleverAge\SyliusDpdPlugin\Model\GetPudoListModel;
use CleverAge\SyliusDpdPlugin\Validator\DpdParamsValidator;
use Sylius\Component\Core\Model\AddressInterface;

final class GetPudoListService extends AbstractService
{
    private const ENDPOINT = '/mypudo/mypudo.asmx/GetPudoList';
    private const DATA = [
        'address', 'city', 'zipCode', 'countryCode'
    ];
    private GetPudoListModelFactory $modelFactory;
    private PudoItemModelFactory $pudoItemModelFactory;

    public function __construct(
        DpdHttpClientFactory    $dpdClient,
        DpdParamsValidator      $validator,
        GetPudoListModelFactory $modelFactory,
        PudoItemModelFactory    $pudoItemModelFactory
    )
    {
        parent::__construct($dpdClient, $validator);
        $this->modelFactory = $modelFactory;
        $this->pudoItemModelFactory = $pudoItemModelFactory;
    }

    private function call(GetPudoListModel $getPudoListModel): array
    {
        return $this->doCall('GET', self::ENDPOINT, $getPudoListModel->toArray());
    }

    public function byCartAddress(AddressInterface $cartAddress, array $options = []): array
    {
        $baseAddress = [
            'address' => $cartAddress->getStreet(),
            'city' => $cartAddress->getCity(),
            'zipCode' => $cartAddress->getPostcode(),
            'countryCode' => $cartAddress->getCountryCode()
        ];

        $model = $this->modelFactory->createFromArray(array_merge($baseAddress, $options));

        return $this->call($model);
    }

    protected function validateDataBeforeCall(array $dataToValidate): void
    {
        $validate = $this->validator->validate($dataToValidate, self::DATA);
        if (!$validate['validate']) {
            $param = (string)$validate['exceptionParam'];
            $getter = '$getPudoListModel->get' . ucfirst($param) . '()';

            throw new MissingGetPudoListSearchArgumentException("Missing $getter value. Please set $param to model.");
        }
    }

    /**
     * @throws MissingGetPudoListSearchArgumentException
     * @throws \JsonException
     */
    protected function parseErrorAndThrow(int $errorCode, $response): void
    {
        if (is_string($response)) {
            throw new MissingGetPudoListSearchArgumentException($response);
        }

        throw new MissingGetPudoListSearchArgumentException(json_encode($response, JSON_THROW_ON_ERROR));
    }

    /**
     * @throws \JsonException
     */
    protected function parseResponse($response): array
    {
        if (!isset($response->PUDO_ITEMS)) {
            return [];
        }

        $pudoList = [];

        foreach ($response->PUDO_ITEMS->PUDO_ITEM as $item) {
            $pudoList[] = $this->pudoItemModelFactory->createFromXml($item);
        }

        return $pudoList;
    }
}
