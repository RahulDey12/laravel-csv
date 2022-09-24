<?php

declare(strict_types=1);

namespace Rahul900day\Csv\Exceptions;

use Exception;

class LogicException extends Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
