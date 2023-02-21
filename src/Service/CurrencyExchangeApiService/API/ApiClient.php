<?php

declare(strict_types=1);

namespace GamesealPlugin\Service\CurrencyExchangeApiService\API;

use GamesealPlugin\Core\Content\CurrencyExchange\CurrencyExchangeEntity;
use GamesealPlugin\Repository\CurrencyExchange\CurrencyExchangeRepository;
use GamesealPlugin\Service\CurrencyExchangeApiService\DTO\CurrencyListApiDTO;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;

class ApiClient
{
    public function __construct(private readonly CurrencyExchangeRepository $currencyExchangeRepository)
    {
    }

    public function getCurrenciesList(Context $context): array
    {
        $result = $this->currencyExchangeRepository->search(new Criteria(), $context);
        $currencies = $result->getElements();
        /** @var  CurrencyListApiDTO[] $dtoList */
        $dtoList = [];
        /** @var CurrencyExchangeEntity $currency */
        foreach ($currencies as $currency) {
            $currencyListApiDTO = new CurrencyListApiDTO();
            $currencyListApiDTO->value = $currency->getValue();
            $currencyListApiDTO->code = $currency->getCode();
            $currencyListApiDTO->lastUpdatedAt = $currency->getLastUpdatedAt();
            $dtoList[] = $currencyListApiDTO;
        }

        return $dtoList;
    }
}
