<?php

namespace App\Seedling;

use Illuminate\Support\Str;

class SeedFile
{
    public function __construct(public readonly string $seeder)
    {
    }

    public function path(): string
    {
        return $this->seeder;
    }

    public function friendlyName(): string
    {
        return implode(' ', Str::ucsplit($this->basename()));
    }

    public function namespace(): string
    {
        return sprintf('\\Database\\Seeders\\%s', $this->basename());
    }

    private function basename(): string
    {
        return basename($this->seeder, '.php');
    }
}
