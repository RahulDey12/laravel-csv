<?php

declare(strict_types=1);

namespace Rahul900day\Csv;

use Illuminate\Support\ServiceProvider;
use League\Csv\Statement;

class CsvServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/csv.php', 'csv'
        );

        $this->app->bind(Statement::class, function () {
            return Statement::create();
        });

        $this->app->singleton('csv', function () {
            return new Factory();
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/csv.php' => config_path('csv.php'),
        ]);
    }
}
