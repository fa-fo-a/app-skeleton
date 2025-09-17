<?php

declare(strict_types=1);

namespace Core\Exception;

use RuntimeException;

class ApplicationException extends RuntimeException
{
    public const string DEFAULT_MESSAGE = 'An undefined application error occurred.';

    public static function throw(
        string $message = self::DEFAULT_MESSAGE,
        int $code = 0,
        ?\Throwable $previous = null
    ): self {
        throw new static($message, $code, $previous);
    }
}
