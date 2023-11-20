<?php

use App\Seedling\PromptHandler;
use App\Seedling\SeedFile;
use App\Seedling\SeedFileCollection;
use Laravel\Prompts\Key;
use Laravel\Prompts\Prompt;

test('returns an empty collection on selecting default option - cancel', function () {
    $collection = SeedFileCollection::fromArray([
        'example/SeederOne.php',
        'example/SeederTwo.php',
    ]);

    Prompt::fake([Key::ENTER]);

    $promptHandler = new PromptHandler();

    $result = $promptHandler->promptUserForSeedChoice($collection);

    expect($result)
        ->toBeInstanceOf(SeedFileCollection::class)
        ->and($result->all())->toEqual([]);
});

test('returns a collection of all seed files on selecting first option - all', function () {
    $collection = SeedFileCollection::fromArray([
        'example/SeederOne.php',
        'example/SeederTwo.php',
    ]);

    Prompt::fake([Key::DOWN, Key::ENTER]);

    $promptHandler = new PromptHandler();

    $result = $promptHandler->promptUserForSeedChoice($collection);

    expect($result)
        ->toBeInstanceOf(SeedFileCollection::class)
        ->each->toBeInstanceOf(SeedFile::class)
        ->and($result->all())
        ->sequence(
            fn($value, $key) => $key->toEqual('example/SeederOne.php'),
            fn($value, $key) => $key->toEqual('example/SeederTwo.php')
        );
});

test('returns a collection of a single seed file', function () {
    $collection = SeedFileCollection::fromArray([
        'example/SeederOne.php',
        'example/SeederTwo.php',
        'example/SeederToBeChosen.php',
    ]);

    Prompt::fake([Key::UP, Key::ENTER]);

    $promptHandler = new PromptHandler();

    $result = $promptHandler->promptUserForSeedChoice($collection);

    expect($result)
        ->toBeInstanceOf(SeedFileCollection::class)
        ->each->toBeInstanceOf(SeedFile::class)
        ->and($result->count())->toBe(1)
        ->and($result->has('example/SeederToBeChosen.php'))->toBeTrue();
});
