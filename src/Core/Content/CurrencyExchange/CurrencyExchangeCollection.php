<?php

declare(strict_types=1);

namespace GamesealPlugin\Core\Content\CurrencyExchange;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

class CurrencyExchangeCollection extends EntityCollection
{
    public function getApiAlias(): string
    {
        return 'gameseal_currency_exchange_collection';
    }

    public function getExpectedClass(): string
    {
        return CurrencyExchangeEntity::class;
    }
}
