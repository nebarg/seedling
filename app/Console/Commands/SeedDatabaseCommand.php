<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Prompts;

class SeedDatabaseCommand extends Command
{
    protected $signature = 'app:seed';

    protected $description = 'Populate your database with fake data.'
        . ' This application does not handle migrations.';

    public function handle(): void
    {
        $availableSeeders = $this->findSeeders();

        $seedsToSow = $this->promptUser($availableSeeders);

        if ($seedsToSow === 'Cancel') {
            $this->info('No seeds have been sown.');

            return;
        }

        if ($seedsToSow === 'All') {
            $seedsToSow = $availableSeeders;
        }

        $this->seed($seedsToSow);
    }

    private function findSeeders(): array
    {
        $files = Storage::disk('database')->allFiles('seeders');

        return array_map(static function ($seeder) {
            return implode(
                ' ',
                Str::ucsplit(
                    basename($seeder, '.php')
                )
            );
        }, $files);
    }

    private function promptUser(array $files): string
    {
        return Prompts\select(
            label: 'What seeds would you like to sow today?',
            options: ['All', ...$files, 'Cancel'],
            default: 'Cancel'
        );
    }

    private function seed(string|array $seeders): void
    {
        foreach ((array) $seeders as $seeder) {
            $namespace = sprintf(
                '\\Database\\Seeders\\%s',
                str_replace(' ', '', $seeder)
            );

            try {
                $seederToRun = $this->laravel->make($namespace)
                    ->setContainer($this->laravel)
                    ->setCommand($this);

                Model::unguarded(static fn() => $seederToRun->__invoke());

                $this->info('Successfully sown ' . $seeder);
            } catch (Exception) {
                $this->error(sprintf('Sowing %s failed', $seeder));
            }
        }
    }
}
