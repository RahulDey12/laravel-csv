<?php

namespace Rahul900day\Csv\Sanitizers;

use Closure;

class TrimString
{
    public function __invoke(string $cell, Closure $next): string
    {
        return $next(trim($cell));
    }
}
