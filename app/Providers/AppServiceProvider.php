<?php

namespace App\Providers;

use App\Exceptions\InvalidConsoleCommandException;
use Illuminate\Console\Events\CommandStarting;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use RuntimeException;

class AppServiceProvider extends ServiceProvider
{
    private array $enabledCommands = [
        'db:seed',
        'make:factory',
        'make:model',
        'make:seed',
        'make:seeder',
        'make:command',
        /** Required for composer */
        'package:discover',
        'vendor:publish',
    ];

    public function register(): void
    {
        if (! App::isLocal()) {
            throw new RuntimeException('This application can only run locally');
        }
    }

    public function boot(): void
    {
        Event::listen(CommandStarting::class, function($event) {
            $command = $event->input->getArgument('command') ?? $event->input->getArguments()[0];

            if (!in_array($command, $this->enabledCommands, true)) {
                throw new InvalidConsoleCommandException(
                    'Standard console commands are disabled. Use `app:seed` to seed data'
                );
            }
        });
    }
}
