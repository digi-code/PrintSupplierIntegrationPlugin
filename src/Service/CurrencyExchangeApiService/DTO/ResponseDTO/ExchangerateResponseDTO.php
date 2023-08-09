<?php

declare(strict_types=1);

namespace PrintSupplierIntegrationPlugin\Service\CurrencyExchangeApiService\DTO\ResponseDTO;

use PrintSupplierIntegrationPlugin\Service\CurrencyExchangeApiService\DTO\DTOInterface;

class ExchangerateResponseDTO implements DTOInterface
{
    public string $date;
    public array $rates;
}
