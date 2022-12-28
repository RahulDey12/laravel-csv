<?php

it('binds factory to csv', function () {
    $csv = app('csv');

    expect($csv)->toBeInstanceOf(\Rahul900day\Csv\Factory::class);
});

it('binds csv as singleton', function () {
    $app = app();

    $csv = $app->get('csv');

    expect($app->get('csv'))->toBe($csv);
});

it('binds csv statement', function () {
    $statement = app(\League\Csv\Statement::class);

    expect($statement)->toBeInstanceOf(\League\Csv\Statement::class);
});
