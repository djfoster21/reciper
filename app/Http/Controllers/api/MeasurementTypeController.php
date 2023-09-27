<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMeasurementTypeRequest;
use App\Http\Requests\UpdateMeasurementTypeRequest;
use App\Models\MeasurementType;

class MeasurementTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMeasurementTypeRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(MeasurementType $measurementType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMeasurementTypeRequest $request, MeasurementType $measurementType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MeasurementType $measurementType)
    {
        //
    }
}
