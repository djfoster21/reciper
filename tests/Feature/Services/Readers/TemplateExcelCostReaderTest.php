<?php

use Illuminate\Support\Collection;

it('reads pseudo excel data', function (Collection $data) {

    $xlsx = Mockery::mock('overload:PhpOffice\PhpSpreadsheet\Reader\Xlsx');
    $xlsx->shouldReceive('load')->once()->andReturnSelf();
    $xlsx->shouldReceive('getSheet')->once()->andReturnSelf();

})->with('read_ingredient_costs_data');
