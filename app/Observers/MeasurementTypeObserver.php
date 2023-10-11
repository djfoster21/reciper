<?php

namespace App\Observers;

use App\Models\MeasurementType;

class MeasurementTypeObserver
{
    /**
     * Handle the MeasurementType "created" event.
     */
    public function creating(MeasurementType $measurementType): void
    {
        $measurementType->account()->associate(auth()->user()->account);
    }

    /**
     * Handle the MeasurementType "updated" event.
     */
    public function updated(MeasurementType $measurementType): void
    {
        //
    }

    /**
     * Handle the MeasurementType "deleted" event.
     */
    public function deleted(MeasurementType $measurementType): void
    {
        //
    }

    /**
     * Handle the MeasurementType "restored" event.
     */
    public function restored(MeasurementType $measurementType): void
    {
        //
    }

    /**
     * Handle the MeasurementType "force deleted" event.
     */
    public function forceDeleted(MeasurementType $measurementType): void
    {
        //
    }
}
