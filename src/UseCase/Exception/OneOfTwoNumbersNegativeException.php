<?php

declare(strict_types=1);

namespace UseCase\Exception;

use Core\Exception\ApplicationException;

class OneOfTwoNumbersNegativeException extends ApplicationException
{
    public const string MESSAGE = 'Both numbers must be positive integers.';

    public static function throwOneOfTwoNumbersNegative(): void
    {
        parent::throw(self::MESSAGE);
    }
}
