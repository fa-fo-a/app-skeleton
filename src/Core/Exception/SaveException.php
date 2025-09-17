<?php

declare(strict_types=1);

namespace Core\Exception;

use Throwable;

class SaveException extends ApplicationException
{
    public const string CANNOT_SAVE_ENTITY = 'Cannot save entity';

    public static function throwCannotSave(Throwable $previous): void
    {
        parent::throw(
            message: self::CANNOT_SAVE_ENTITY,
            previous: $previous
        );
    }
}
