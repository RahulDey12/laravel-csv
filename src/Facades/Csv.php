<?php

declare(strict_types=1);

namespace Rahul900day\Csv\Facades;

use Illuminate\Support\Facades\Facade;
use SplFileObject;

/**
 * @method static \Rahul900day\Csv\Csv fromDisk(string $disk, string $path, string $open_mode = 'r')
 * @method static \Rahul900day\Csv\Csv fromFileObject(SplFileObject $file)
 * @method static \Rahul900day\Csv\Csv fromPath(string $path, string $open_mode = 'r')
 * @method static \Rahul900day\Csv\Csv fromStream($stream)
 * @method static \Rahul900day\Csv\Csv fromString(string $content)
 *
 * @see \Rahul900day\Csv\Factory
 */
class Csv extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'csv';
    }
}
