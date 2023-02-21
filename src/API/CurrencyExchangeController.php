<?php

declare(strict_types=1);

namespace GamesealPlugin\API;

use GamesealPlugin\Service\CurrencyExchangeApiService\API\ApiClient;
use Shopware\Core\Framework\Context;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route(defaults={"_routeScope"={"api"}})
 */
class CurrencyExchangeController extends AbstractController
{
    public function __construct(private readonly ApiClient $apiClient, private readonly Serializer $serializer)
    {}

    /**
     * @Route("/api/_action/gameseal/get-currencies-data", name="api.action.gameseal.get_currencies_data", methods={"GET"}, defaults={"XmlHttpRequest"=true})
     */
    public function getCurrenciesData(Context $context): JsonResponse
    {
        try {
            $data = $this->apiClient->getCurrenciesList($context);
            return new JsonResponse($this->serializer->serialize($data, 'json'), Response::HTTP_OK, [], true);
        } catch (\Exception $exception) {
            return new JsonResponse($exception->getMessage(), $exception->getCode());
        }
    }
}
