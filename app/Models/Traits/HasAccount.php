<?php

namespace App\Models\Traits;

use App\Models\Account;
use App\Models\Scopes\AccountScope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasAccount
{
    public static function booted(): void
    {
        parent::booted();
        static::addGlobalScope(new AccountScope());
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
