<?php

namespace App\Services\Readers;

use Illuminate\Support\Collection;

interface ReaderInterface
{
    public function handle(string $file): Collection;
}
