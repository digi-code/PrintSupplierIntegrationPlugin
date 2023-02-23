<?php

declare(strict_types=1);

namespace GamesealPlugin\Service\CurrencyExchangeApiService\Client;

use GamesealPlugin\Repository\CurrencyExchange\CurrencyExchangeRepository;
use GamesealPlugin\Service\CurrencyExchangeApiService\DTO\CurrencyDTO;
use GuzzleHttp\Client;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class CurrencyApiClient extends AbstractClient
{
    public function run(): void
    {
        $url = sprintf('%s/%s?base_currency=%s&currencies=%s', $this->apiUrl, 'latest', $this->baseCurrency, $this->currenciesCodes);
        $data = $this->makeRequest('GET', $url);
        //^ "{"meta":{"last_updated_at":"2023-02-21T23:59:59Z"},"data":{"CZK":{"code":"CZK","value":4.996696},"EUR":{"code":"EUR","value":0.210566},"USD":{"code":"USD","value":0.224288}}}"
        $dataArray['lastUpdatedAt'] = $data['meta']['last_updated_at'];

        foreach ($data['data'] as $currency) {
            $currency = array_merge($currency, $dataArray);
            /** @var CurrencyDTO $dto */
            $dto = $this->serializer->deserialize(json_encode($currency), CurrencyDTO::class, 'json');
            $this->writeData($dto);
        }
    }
}
