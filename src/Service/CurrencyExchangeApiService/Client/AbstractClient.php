<?php

declare(strict_types=1);

namespace GamesealPlugin\Service\CurrencyExchangeApiService\Client;

use GamesealPlugin\Core\Content\CurrencyExchange\CurrencyExchangeSource;
use GamesealPlugin\Service\CurrencyExchangeApiService\DTO\DTOInterface;
use GuzzleHttp\Exception\GuzzleException;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Shopware\Core\System\Currency\CurrencyEntity;


abstract class AbstractClient
{
    public string $currenciesCodes;
    public string $baseCurrency;
    public ?string $apiKey;

    public function __construct(
        protected SystemConfigService $configService,
        protected SerializerInterface $serializer,
        protected Client $client,
        protected EntityRepository $entityRepository,
        protected ContainerInterface $container,
    )
    {
        $this->apiKey = $this->configService->get('GamesealPlugin.config.apiKey');
        $baseCurrencyId = $this->configService->get('GamesealPlugin.config.baseCurrency');
        $currenciesIdsToRate = $this->configService->get('GamesealPlugin.config.currenciesToRate');

        if (!$baseCurrencyId || !$currenciesIdsToRate) {
            throw new \Exception('Base Currency or CurrencyToRate params are missing in plugin config');
        } else {
            $this->currenciesCodes = $this->getCurrenciesRateCodesString($currenciesIdsToRate);
            $this->baseCurrency = $this->getCurrencyCodeById($baseCurrencyId);
        }
    }

    abstract public function run(): void;
    public function createContext(DTOInterface $dto): Context
    {
        return new Context(new CurrencyExchangeSource($dto->code, $dto->value, $dto->lastUpdatedAt));
    }

    /**
     * @throws GuzzleException
     */
    protected function makeRequest(string $method, string $url): array
    {
        $response = $this->client->send(new Request($method, $url, ['apiKey' => $this->apiKey]));
        $data = $response->getBody()->getContents();

        return json_decode($data, true);
    }

    protected function writeData(DTOInterface $dto): void
    {
        $context = $this->createContext($dto);
        $currencyId = $this->findCurrencyIdByCode($context);

        if (!$currencyId) {
            $this->entityRepository->create([$this->dtoToArray($dto)], $context);
        } else {
            $dto->id = $currencyId;
            $this->entityRepository->update([$this->dtoToArray($dto)], $context);
        }
    }

    private function dtoToArray(DTOInterface $dto): array
    {
        return json_decode($this->serializer->serialize($dto, 'json'), true);
    }

    private function findCurrencyIdByCode(Context $context): ?string
    {
        /** @var CurrencyExchangeSource $source */
        $source = $context->getSource();
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('code', $source->getCode()));

        return $this->entityRepository->searchIds($criteria, $context)->firstId();
    }

    private function getCurrenciesRateCodesString(array $currencyIds): string
    {
        $data = array_map(fn($currencyId): string => $this->getCurrencyCodeById($currencyId), $currencyIds);

        return implode(',', $data);
    }

    private function getCurrencyCodeById(string $currencyId): string
    {
        /** @var EntityRepository $currencyRepo */
        $currencyRepo = $this->container->get('currency.repository');
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('id', $currencyId));
        /** @var CurrencyEntity $currency */
        $currency = $currencyRepo->search($criteria, Context::createDefaultContext())->first();

        return  $currency->getIsoCode();
    }
}
