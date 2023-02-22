<?php

declare(strict_types=1);

namespace GamesealPlugin\Service\CurrencyExchangeApiService\DTO\ResponseDTO;

use GamesealPlugin\Service\CurrencyExchangeApiService\DTO\DTOInterface;

class ExchangerateResponseDTO implements DTOInterface
{
    public string $date;
    public array $rates;
}
