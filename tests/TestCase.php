<?php

namespace Rahul900day\Csv\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Rahul900day\Csv\CsvServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            CsvServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        //
    }
}
