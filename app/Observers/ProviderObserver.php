<?php

namespace App\Observers;

use App\Models\Provider;

class ProviderObserver
{
    /**
     * Handle the Provider "created" event.
     */
    public function creating(Provider $provider): void
    {
        $provider->account()->associate(auth()->user()->account);
    }

    /**
     * Handle the Provider "updated" event.
     */
    public function updated(Provider $provider): void
    {
        //
    }

    /**
     * Handle the Provider "deleted" event.
     */
    public function deleted(Provider $provider): void
    {
        //
    }

    /**
     * Handle the Provider "restored" event.
     */
    public function restored(Provider $provider): void
    {
        //
    }

    /**
     * Handle the Provider "force deleted" event.
     */
    public function forceDeleted(Provider $provider): void
    {
        //
    }
}
