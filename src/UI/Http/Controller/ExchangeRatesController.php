<?php

declare(strict_types=1);

namespace UI\Http\Controller;

use UseCase\DTO\ExchangeRateRequestDTO;
use UI\Http\Factory\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use UseCase\GetExchangeRatesHandler;
use Psr\Http\Message\ServerRequestInterface;

class ExchangeRatesController
{
    public const string ROUTE = '/exchange-rates/{currencyFrom}/{currencyTo}';
    public const array METHODS = ['GET'];

    public function __construct(
        private readonly GetExchangeRatesHandler $getExchangeRatesHandler,
        private readonly ResponseFactory $responseFactory,
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        /** @var array<string, string> $pathParams */
        $pathParams = $request->getAttribute('_route_params', []);

        $errors = $this->validatePathParams($pathParams);
        if (!empty($errors)) {
            return $this->responseFactory->build(
                400,
                (string) json_encode(
                    [
                        'errors' => $errors,
                    ],
                    JSON_PRETTY_PRINT
                )
            );
        }

        /** @var string $currencyFrom */
        $currencyFrom = $pathParams['currencyFrom'];
        /** @var string $currencyTo */
        $currencyTo = $pathParams['currencyTo'];

        $exchangeRates = $this->getExchangeRatesHandler->handle(
            new ExchangeRateRequestDTO($currencyFrom, $currencyTo)
        );

        $data = array_map(static function ($exchangeRate) {
            return [
                'currency_from' => $exchangeRate->getCurrencyFrom(),
                'currency_to' => $exchangeRate->getCurrencyTo(),
                'rate' => $exchangeRate->getRate(),
                'date' => $exchangeRate->getDate()->format('Y-m-d'),
            ];
        }, $exchangeRates);

        return $this->responseFactory->build(
            200,
            (string) json_encode(
                [
                    'data' => $data,
                    'count' => count($data),
                ],
                JSON_PRETTY_PRINT
            )
        );
    }

    /**
     * @param array<string, mixed> $pathParams
     * @return string[]
     */
    private function validatePathParams(array $pathParams): array
    {
        $errors = [];

        if (
            !isset($pathParams['currencyFrom']) ||
            !is_string($pathParams['currencyFrom']) ||
            strlen($pathParams['currencyFrom']) !== 3
        ) {
            $errors[] = 'Invalid currency_from parameter. Must be 3-character currency code.';
        }

        if (
            !isset($pathParams['currencyTo']) ||
            !is_string($pathParams['currencyTo']) ||
            strlen($pathParams['currencyTo']) !== 3
        ) {
            $errors[] = 'Invalid currency_to parameter. Must be 3-character currency code.';
        }

        return $errors;
    }
}
