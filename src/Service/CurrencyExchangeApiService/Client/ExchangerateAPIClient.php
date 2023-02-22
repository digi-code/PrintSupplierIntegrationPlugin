<?php

declare(strict_types=1);

namespace GamesealPlugin\Service\CurrencyExchangeApiService\Client;

use GamesealPlugin\Service\CurrencyExchangeApiService\DTO\CurrencyDTO;
use GamesealPlugin\Service\CurrencyExchangeApiService\DTO\ResponseDTO\ExchangerateResponseDTO;

class ExchangerateAPIClient extends AbstractClient
{
    public function run(): void
    {
        $url = sprintf('%s/%s?base=%s&symbols=%s', $this->apiUrl, 'latest', $this->baseCurrency, $this->currenciesCodes);
        $data = $this->makeRequest('GET', $url, true);
        /** @var ExchangerateResponseDTO $responseDTO */
        $responseDTO = $this->serializer->deserialize($data, ExchangerateResponseDTO::class, 'json');

        foreach ($responseDTO->rates as $code => $value) {
            $dto = new CurrencyDTO();
            $dto->code = $code;
            $dto->value = $value;
            $dto->lastUpdatedAt = $responseDTO->date;

            $this->writeData($dto);
        }
    }

}
