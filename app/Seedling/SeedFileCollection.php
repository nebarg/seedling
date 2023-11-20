<?php

namespace App\Seedling;

use Illuminate\Support\Collection;

class SeedFileCollection extends Collection
{
    public static function fromArray(array $seeders): SeedFileCollection
    {
        $files = array_reduce(
            $seeders,
            static function ($result, $filepath) {
                $result[$filepath] = new SeedFile($filepath);

                return $result;
            },
            []
        );

        return new static($files);
    }
}
