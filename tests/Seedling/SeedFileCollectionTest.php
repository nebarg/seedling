<?php

use App\Seedling\SeedFileCollection;
use App\Seedling\SeedFile;
use Illuminate\Support\Collection;

test('SeedFileCollection is an instance of Illuminate\Support\Collection')
    ->expect(new SeedFileCollection([]))
    ->toBeInstanceOf(Collection::class);

test('fromArray creates a collection of SeedFiles')
    ->expect(
        SeedFileCollection::fromArray([
            'example/SeedFile.php',
            'example/SeedFileTwo.php',
        ])
    )
    ->each->toBeInstanceOf(SeedFile::class);
