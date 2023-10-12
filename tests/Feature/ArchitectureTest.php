<?php

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Seeder;

test('Database Dumps directory exists and is readable')
    ->expect('database/dumps')
    ->toBeReadableDirectory();

test('Database Factories directory exists and is readable')
    ->expect('database/factories')
    ->toBeReadableDirectory();

test('Database Seeders directory exists and is readable')
    ->expect('database/seeders')
    ->toBeReadableDirectory();

test('Seeders must extend the framework seeder class')
    ->expect('Database\Seeders')
    ->toExtend(Seeder::class);

test('Factories must extend the framework factory class')
    ->expect('Database\Factories')
    ->toExtend(Factory::class);

test('No forgotten dumps, exits or dev tools')
    ->expect(['dd', 'dump', 'var_dump', 'ray', 'die', 'exit'])
    ->not->toBeUsed();
