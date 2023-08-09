<?php

declare(strict_types=1);

namespace PrintSupplierIntegrationPlugin\Core\Content\CurrencyExchange;

use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\CreatedAtField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\DateTimeField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FloatField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\UpdatedAtField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class CurrencyExchangeDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'gameseal_currency_exchange';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new Required(), new PrimaryKey()),
            (new StringField('code', 'code'))->addFlags(new Required()),
            (new FloatField('value', 'value'))->addFlags(new Required()),
            (new DateTimeField('last_updated_at', 'lastUpdatedAt')),
        ]);
    }

    public function getEntityClass(): string
    {
        return CurrencyExchangeEntity::class;
    }

    public function getCollectionClass(): string
    {
        return CurrencyExchangeCollection::class;
    }
}
