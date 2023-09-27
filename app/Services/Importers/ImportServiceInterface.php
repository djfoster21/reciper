<?php

namespace App\Services\Importers;

use App\Models\Account;
use Illuminate\Support\Collection;

interface ImportServiceInterface
{
    public function handle(string $file, Account $account): Collection;
}
