<?php

declare(strict_types=1);

namespace PrintSupplierIntegrationPlugin\Service\CurrencyExchangeApiService\Client;

use PrintSupplierIntegrationPlugin\Core\Content\CurrencyExchange\CurrencyExchangeSource;
use PrintSupplierIntegrationPlugin\Service\CurrencyExchangeApiService\DTO\DTOInterface;
use GuzzleHttp\Exception\GuzzleException;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Serializer\SerializerInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Shopware\Core\System\Currency\CurrencyEntity;


abstract class AbstractClient
{
    public string $currenciesCodes;
    public string $baseCurrency;
    public string $apiUrl;
    public string $apiProvider;
    public ?string $apiKey;

    public function __construct(
        protected SystemConfigService $configService,
        protected SerializerInterface $serializer,
        protected Client $client,
        protected EntityRepository $entityRepository,
        protected ContainerInterface $container,
        protected ParameterBagInterface $parameterBag,
    )
    {
        $this->baseCurrency = $this->getBaseCurrencyCode();
        $this->currenciesCodes = $this->getCurrenciesCodes();
        $this->apiKey = $this->configService->get('GamesealPlugin.config.apiKey');
        $this->apiProvider = $this->configService->get('GamesealPlugin.config.apiProvider');
        $this->apiUrl = $this->parameterBag->get(sprintf('%s.api_url', $this->apiProvider));
    }

    abstract public function run(): void;
    public function createContext(DTOInterface $dto): Context
    {
        return new Context(new CurrencyExchangeSource($dto->code, $dto->value, $dto->lastUpdatedAt));
    }

    /**
     * @throws GuzzleException
     */
    protected function makeRequest(string $method, string $url, bool $rawData = false)
    {
        $response = $this->client->send(new Request($method, $url, ['apiKey' => $this->apiKey]));
        $data = $response->getBody()->getContents();

        return $rawData ? $data : json_decode($data, true);
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

    private function getCurrenciesCodes(): string
    {
        $currenciesIds = $this->configService->get('GamesealPlugin.config.currenciesToRate');

        if (!$currenciesIds) {
            throw new \Exception('CurrencyToRate param is missing in plugin config');
        }

        return $this->getCurrenciesRateCodesString($currenciesIds);
    }

    private function getBaseCurrencyCode(): string
    {
        $baseCurrencyId = $this->configService->get('GamesealPlugin.config.baseCurrency');

        if (!$baseCurrencyId) {
            throw new \Exception('BaseCurrency param is missing in plugin config');
        }

        return $this->getCurrencyCodeById($baseCurrencyId);
    }

}
