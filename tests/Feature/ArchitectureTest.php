<?php

test('Seeders ')
    ->expect('Database\Seeders')
    ->toExtend('Illuminate\Database\Seeder');

test('No forgotten dumps, exits or dev tools')
    ->expect(['dd', 'dump', 'ray', 'var_dump', 'exit'])
    ->not->toBeUsed();
