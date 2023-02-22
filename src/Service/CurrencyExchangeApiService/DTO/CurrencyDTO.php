<?php

declare(strict_types=1);

namespace GamesealPlugin\Service\CurrencyExchangeApiService\DTO;

class CurrencyDTO implements DTOInterface
{
    public string $code;
    public float $value;
    public string $lastUpdatedAt;
    public ?string $id;
}
