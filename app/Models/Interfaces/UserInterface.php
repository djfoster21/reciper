<?php

namespace App\Models\Interfaces;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

interface UserInterface
{
    public function account(): BelongsTo;
}
