<?php

namespace App\Seedling;

use Laravel\Prompts;

class PromptHandler
{
    public function promptUserForSeedChoice(SeedFileCollection $seeders): SeedFileCollection
    {
        $result = $this->promptUser($this->generatePromptOptions($seeders));

        return $result === 'all' ? $seeders : $seeders->only($result);
    }

    private function promptUser(array $options): string
    {
        return Prompts\select(
            label: 'What seeds would you like to sow today?',
            options: $options,
            default: 'cancel'
        );
    }

    private function generatePromptOptions(SeedFileCollection $seeders): array
    {
        return [
            'all' => 'All Seeders',
            ...$seeders->map(fn ($item) => $item->friendlyName()),
            'cancel' => 'Cancel',
        ];
    }
}
