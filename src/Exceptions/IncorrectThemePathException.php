<?php

declare(strict_types=1);

namespace Juaniquillo\BackendComponents\Exceptions;

use Throwable;

class IncorrectThemePathException extends \Exception
{
    public function __construct(string $message, int $code = 500, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
