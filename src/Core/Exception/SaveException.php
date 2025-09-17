<?php

declare(strict_types=1);

use Core\Exception\ApplicationException;

class SaveException extends ApplicationException
{
    public const string CANNOT_SAVE_ENTITY = 'Cannot save entity';

    public static function throwCannotSave(): void
    {
        parent::throw(self::CANNOT_SAVE_ENTITY);
    }
}
