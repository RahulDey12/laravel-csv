<?php

declare(strict_types=1);

namespace Rahul900day\CSV;

use Illuminate\Support\ServiceProvider;

class CsvServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/csv.php', 'csv'
        );
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/csv.php' => config_path('csv.php'),
        ]);
    }
}
