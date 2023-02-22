<?php

declare(strict_types=1);

namespace GamesealPlugin\Service\CurrencyExchangeApiService\Client;

use GamesealPlugin\Repository\CurrencyExchange\CurrencyExchangeRepository;
use GuzzleHttp\Client;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Symfony\Component\Serializer\SerializerInterface;

class ExchangerateAPIClient extends AbstractClient
{
    private const API_URL = 'https://api.exchangerate.host';

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
        $url = sprintf('%s/%s?base=%s&symbols=%s', self::API_URL, 'latest', 'PLN', 'EUR,USD');
        $request = $this->makeRequest('GET', $url);
        $data = $request->getBody()->getContents();
        dd($data);
    }

}
