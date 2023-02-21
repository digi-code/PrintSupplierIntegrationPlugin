<?php

declare(strict_types=1);

namespace GamesealPlugin\Exception\CurrencyExchangeServiceException;

use Symfony\Component\HttpFoundation\Response;

class ApiProviderNotDefinedException extends \Exception
{
    public function __construct(string $message = "", int $code = Response::HTTP_NOT_FOUND, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
