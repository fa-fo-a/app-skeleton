<?php

declare(strict_types=1);

namespace UI\Http\Controller;

use Core\Exception\ApplicationException;
use UseCase\DTO\TwoNumbersDTO;
use UI\Http\Factory\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use UseCase\SumTwoPositiveNumbersHandler;
use Psr\Http\Message\ServerRequestInterface;

class SumTwoPositiveNumbersController
{
    public const string ROUTE = '/';
    public const array METHODS = ['POST'];

    public function __construct(
        private SumTwoPositiveNumbersHandler $sumTwoPositiveNumbersHandler,
        private ResponseFactory $responseFactory
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $parsedBody = json_decode(
            $request
                ->getBody()
                ->getContents(),
            true
        );
        /** @var array<string, string|int> $parsedBody */
        $queryParams = is_array($parsedBody) ? $parsedBody : [];
        $errors = $this->validateQueryParams($queryParams);
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

        $a = $queryParams['a'];
        $b = $queryParams['b'];
        /** @var int $a */
        /** @var int $b */

        try {
            $result = $this->sumTwoPositiveNumbersHandler->handle(
                new TwoNumbersDTO(
                    $a,
                    $b
                )
            );
        } catch (ApplicationException $e) {
            return $this->responseFactory->build(
                400,
                (string) json_encode(
                    [
                        'errors' => [$e->getMessage()],
                    ],
                    JSON_PRETTY_PRINT
                )
            );
        }


        return $this->responseFactory->build(
            200,
            (string) json_encode(
                [
                    'result' => $result->getResult(),
                ],
                JSON_PRETTY_PRINT
            )
        );
    }

    /**
     * @param array<string, string|int> $queryParams
     * @return string[]
     */
    private function validateQueryParams(array $queryParams): array
    {
        $errors = [];

        if (!isset($queryParams['a']) || !is_numeric($queryParams['a'])) {
            $errors[] = 'Invalid or missing parameter "a".';
        }

        if (!isset($queryParams['b']) || !is_numeric($queryParams['b'])) {
            $errors[] = 'Invalid or missing parameter "b".';
        }

        return $errors;
    }
}
