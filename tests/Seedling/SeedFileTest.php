<?php

use App\Seedling\SeedFile;

test('path returns full path', function () {
    $path = pathinfo(__FILE__, PATHINFO_DIRNAME);
    $dto = new SeedFile($path);

    expect($dto->path())->toEqual($path);
});

test('friendly name returns more readable seed file names', function () {
    $dto = new SeedFile('example/path/ThisIsOurSeeder.php');

    expect($dto->friendlyName())->toEqual('This Is Our Seeder');
});

test('namespace returns database seeder namespace', function () {
    $dto = new SeedFile('seeders/ExampleSeeder.php');

    expect($dto->namespace())->toEqual('\\Database\\Seeders\\ExampleSeeder');
});
