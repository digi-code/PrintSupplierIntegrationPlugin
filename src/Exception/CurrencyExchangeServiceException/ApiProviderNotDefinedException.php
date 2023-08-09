<?php

declare(strict_types=1);

namespace PrintSupplierIntegrationPlugin\Exception\CurrencyExchangeServiceException;

use Symfony\Component\HttpFoundation\Response;

class ApiProviderNotDefinedException extends \Exception
{
    public function __construct(string $message = 'apiProvider value not found in plugin config', int $code = Response::HTTP_NOT_FOUND, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
