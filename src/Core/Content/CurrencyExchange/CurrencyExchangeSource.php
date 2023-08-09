<?php

declare(strict_types=1);

namespace PrintSupplierIntegrationPlugin\Core\Content\CurrencyExchange;

use Shopware\Core\Framework\Api\Context\ContextSource;

class CurrencyExchangeSource implements ContextSource
{
    private ?string $id;
    private string $code;
    private float $value;
    private string $lastUpdatedAt;


    public function __construct(string $code, float $value, string $lastUpdatedAt, ?string $id = null)
    {
        $this->code = $code;
        $this->value = $value;
        $this->lastUpdatedAt = $lastUpdatedAt;
        $this->id = $id;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function getLastUpdatedAt(): string
    {
        return $this->lastUpdatedAt;
    }
}
