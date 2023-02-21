<?php

declare(strict_types=1);

namespace GamesealPlugin\Service\CurrencyExchangeApiService\DTO;

class CurrencyListApiDTO
{
    public string $code;
    public float $value;
    public \DateTimeImmutable $lastUpdatedAt;
}
