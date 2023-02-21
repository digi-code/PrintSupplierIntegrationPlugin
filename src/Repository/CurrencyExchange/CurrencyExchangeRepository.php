<?php

declare(strict_types=1);

namespace GamesealPlugin\Repository\CurrencyExchange;

use GamesealPlugin\Core\Content\CurrencyExchange\CurrencyExchangeDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityLoadedEventFactory;
use Shopware\Core\Framework\DataAbstractionLayer\Read\EntityReaderInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\EntityAggregatorInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\EntitySearcherInterface;
use Shopware\Core\Framework\DataAbstractionLayer\VersionManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CurrencyExchangeRepository extends EntityRepository
{
    public function __construct(
        CurrencyExchangeDefinition $definition,
        EntityReaderInterface $reader,
        VersionManager $versionManager,
        EntitySearcherInterface $searcher,
        EntityAggregatorInterface $aggregator,
        EventDispatcherInterface $eventDispatcher, ?
        EntityLoadedEventFactory $eventFactory = null
    )
    {
        parent::__construct($definition, $reader, $versionManager, $searcher, $aggregator, $eventDispatcher, $eventFactory);
    }
}
