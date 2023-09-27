<?php

namespace App\Services\Readers;

use Cknow\Money\Money;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class TemplateExcelCostReader implements ReaderInterface
{
    private const SHEET_NUMBER = 1;

    private const ROW_START = 1;

    private const COLUMN_INGREDIENT_NAME = 0;

    private const COLUMN_INGREDIENT_COST_QUANTITY = 1;

    private const COLUMN_INGREDIENT_COST_UNITY_TYPE = 2;

    private const COLUMN_INGREDIENT_COST_PRICE = 3;

    private const COLUMN_INGREDIENT_COST_PROVIDER = 5;

    public function handle(string $file): Collection
    {
        $reader = new Xlsx();
        $spreadsheet = $reader->load($file);
        $worksheet = $spreadsheet->getSheet(self::SHEET_NUMBER);

        $rows = collect($worksheet->toArray());
        $ingredients = collect();
        $count = count($rows);
        for ($i = self::ROW_START; $i < $count; $i++) {
            $row = $rows[$i];
            if ($row[self::COLUMN_INGREDIENT_NAME]) {
                $ingredients->push(collect([
                    'name' => $this->normalizeOutput($row[self::COLUMN_INGREDIENT_NAME]),
                    'cost_quantity' => $this->normalizeOutput($row[self::COLUMN_INGREDIENT_COST_QUANTITY]),
                    'cost_unit_type' => $this->normalizeOutput($row[self::COLUMN_INGREDIENT_COST_UNITY_TYPE]),
                    'cost_price' => $row[self::COLUMN_INGREDIENT_COST_PRICE],
                    'cost_provider' => $this->normalizeOutput($row[self::COLUMN_INGREDIENT_COST_PROVIDER]),
                ]));
            }
        }

        return $ingredients;
    }

    protected function normalizeOutput(?string $string): ?string
    {
        return trim(strtolower($string));
    }
}
