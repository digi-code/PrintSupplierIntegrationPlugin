<?php

declare(strict_types=1);

namespace GamesealPlugin\Service\CurrencyExchangeApiService\DTO;

class CurrencyListDTO
{
    public string $code;
    public float $value;
    public \DateTimeImmutable $lastUpdatedAt;
}
