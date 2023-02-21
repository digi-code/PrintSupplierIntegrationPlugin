<?php

declare(strict_types=1);

namespace GamesealPlugin\Service\CurrencyExchangeApiService\Client;

use GamesealPlugin\Repository\CurrencyExchange\CurrencyExchangeRepository;
use GamesealPlugin\Service\CurrencyExchangeApiService\DTO\CurrencyApiClientDTO;
use GuzzleHttp\Client;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Symfony\Component\Serializer\SerializerInterface;

class CurrencyApiClient extends AbstractClient
{
    private const API_URL = 'https://api.currencyapi.com/v3';

    public function __construct(
        SystemConfigService $configService,
        SerializerInterface $serializer,
        Client $client,
        CurrencyExchangeRepository $currencyExchangeRepository,
    )
    {
        parent::__construct($configService, $serializer, $client, $currencyExchangeRepository);
    }

    public function run(): void
    {
        $url = sprintf('%s/%s?base_currency=%s&currencies=%s', self::API_URL, 'latest', 'PLN', 'EUR,USD');
        $response = $this->makeRequest('GET', $url);
        $data = $response->getBody()->getContents();
        $body = json_decode($data, true);
        $dataArray['lastUpdatedAt'] = $body['meta']['last_updated_at'];

        foreach ($body['data'] as $currency) {
            $currency = array_merge($currency, $dataArray);
            /** @var CurrencyApiClientDTO $dto */
            $dto = $this->serializer->deserialize(json_encode($currency), CurrencyApiClientDTO::class, 'json');
            $this->writeData($dto);
        }
    }
}
