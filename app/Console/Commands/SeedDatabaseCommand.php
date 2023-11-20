<?php

namespace App\Console\Commands;

use App\Seedling\PromptHandler;
use App\Seedling\SeedFileCollection;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SeedDatabaseCommand extends Command
{
    protected $signature = 'db:seed';

    protected $description = 'Populate your database with fake data.'
        .' This application does not handle migrations.';

    private PromptHandler $prompt;

    private SeedFileCollection $seeders;

    public function __construct(PromptHandler $prompt)
    {
        parent::__construct();

        $this->prompt = $prompt;

        $this->seeders = SeedFileCollection::fromArray(
            Storage::disk('database')->allFiles('seeders')
        );
    }

    public function handle(): void
    {
        $seeders = $this->prompt->promptUserForSeedChoice($this->seeders);

        if ($seeders->isEmpty()) {
            $this->info('No seeds have been sown.');

            return;
        }

        $this->seed($seeders);
    }

    private function seed(SeedFileCollection $seeders): void
    {
        $seeders->map(function ($seeder) {
            try {
                $seederToRun = $this->laravel->make($seeder->namespace())
                    ->setContainer($this->laravel)
                    ->setCommand($this);

                Model::unguarded(static fn() => $seederToRun->__invoke());

                $this->info("Successfully sown {$seeder->friendlyName()}");
            } catch (Exception) {
                $this->error("Sowing {$seeder->friendlyName()} failed");
            }
        });
    }
}
