<?php

declare(strict_types=1);

namespace PrintSupplierIntegrationPlugin\Core\Content\CurrencyExchange;

use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class CurrencyExchangeEntity extends Entity
{
    use EntityIdTrait;

    protected string $code;
    protected float $value;
    protected ?\DateTimeInterface $lastUpdatedAt;

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): CurrencyExchangeEntity
    {
        $this->code = $code;
        return $this;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function setValue(float $value): CurrencyExchangeEntity
    {
        $this->value = $value;
        return $this;
    }

    public function getLastUpdatedAt(): ?\DateTimeInterface
    {
        return $this->lastUpdatedAt;
    }

    public function setLastUpdatedAt(?\DateTimeInterface $lastUpdatedAt): CurrencyExchangeEntity
    {
        $this->lastUpdatedAt = $lastUpdatedAt;
        return $this;
    }
}
