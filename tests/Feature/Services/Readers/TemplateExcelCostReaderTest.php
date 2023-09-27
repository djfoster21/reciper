<?php

use App\Services\Readers\TemplateExcelCostReader;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

it('reads pseudo excel data', function (Collection $data) {
    $worksheet = Mockery::mock('overload:'.Worksheet::class);
    $worksheet->shouldReceive('toArray')->once()->andReturn($data);
    $xlsx = Mockery::mock('overload:'.Xlsx::class);
    $xlsx->shouldReceive('load')->once()->andReturnSelf();
    $xlsx->shouldReceive('getSheet')->once()->andReturn($worksheet);

    $result = (new TemplateExcelCostReader())->handle('file');

})->with('read_ingredient_costs_data');
