<?php

declare(strict_types=1);

namespace CleverAge\SyliusDpdPlugin\Service;

use CleverAge\SyliusDpdPlugin\Factory\Client\DpdHttpClientFactory;
use CleverAge\SyliusDpdPlugin\Validator\DpdParamsValidator;
use Exception;
use RuntimeException;
use SimpleXMLElement;

abstract class AbstractService
{
    private DpdHttpClientFactory $dpdClient;
    protected DpdParamsValidator $validator;

    public function __construct(
        DpdHttpClientFactory $dpdClient,
        DpdParamsValidator   $validator
    )
    {
        $this->dpdClient = $dpdClient;
        $this->validator = $validator;
    }

    protected function doCall(
        string $method,
        string $url,
        array  $options = []
    )
    {
        $this->validateDataBeforeCall($options);

        try {
            $response = $this->dpdClient->call($method, $url, $options);

            $xml = new SimpleXMLElement($response->getContent());

            // If ERROR exists in XML, throw exception
            if (isset($xml->ERROR)) {
                throw new RuntimeException((string)$xml->ERROR, 400);
            }

        } catch (Exception $e) {
            $this->parseErrorAndThrow(
                $e->getCode(),
                method_exists($e, 'getResponse') ?
                    $e->getResponse()->getContent(false) :
                    $e->getMessage()
            );
        }

        return $this->parseResponse($xml);
    }

    abstract protected function validateDataBeforeCall(array $dataToValidate): void;

    /** @param string|SimpleXMLElement $response */
    abstract protected function parseErrorAndThrow(int $errorCode, $response): void;

    /**
     * @param string|SimpleXMLElement $response
     *
     * @return mixed
     */
    abstract protected function parseResponse($response);
}
