<?php

namespace Rahul900day\Csv\Exceptions;

use Exception;

class ColumnDoesNotExists extends Exception
{
    public function __construct(?string $message = null)
    {
        parent::__construct($message ?? 'Column Does Not Exists');
    }
}
