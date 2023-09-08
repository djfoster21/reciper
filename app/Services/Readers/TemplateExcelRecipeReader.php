<?php

namespace App\Services\Readers;

use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class TemplateExcelRecipeReader implements ReaderInterface
{
    private const SHEET_NUMBER = 0;

    private const ROW_START = 1;

    private const COLUMN_RECIPE_NAME = 0;

    private const COLUMN_RECIPE_YIELD = 3;

    private const COLUMN_RECIPE_NOTES = 4;

    private const COLUMN_INGREDIENT_NAME = 2;

    private const COLUMN_INGREDIENT_QUANTITY = 3;

    private const COLUMN_INGREDIENT_UNIT_TYPE = 4;

    /**
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function handle(string $file): Collection
    {
        $reader = new Xlsx();
        $spreadsheet = $reader->load($file);
        $worksheet = $spreadsheet->getSheet(self::SHEET_NUMBER);

        $rows = collect($worksheet->toArray());
        $recipes = collect([]);
        $currentRecipe = null;
        $count = count($rows);
        for ($i = self::ROW_START; $i < $count; $i++) {
            $row = $rows[$i];
            if ($row[self::COLUMN_RECIPE_NAME] && ! $row[self::COLUMN_INGREDIENT_NAME]) {
                if ($currentRecipe) {
                    $recipes->push($currentRecipe);
                }
                $currentRecipe = collect([
                    'name' => $this->normalizeOutput($row[self::COLUMN_RECIPE_NAME]),
                    'yield' => $this->normalizeOutput($row[self::COLUMN_RECIPE_YIELD]),
                    'category' => '',
                    'notes' => $this->normalizeOutput($row[self::COLUMN_RECIPE_NOTES]),
                    'ingredients' => collect([]),
                ]);

                continue;
            }
            if ($row[self::COLUMN_INGREDIENT_NAME]) {
                $currentRecipe['ingredients']->push(collect([
                    'name' => $this->normalizeOutput($row[self::COLUMN_INGREDIENT_NAME]),
                    'quantity' => $this->normalizeOutput($row[self::COLUMN_INGREDIENT_QUANTITY]),
                    'unit_type' => $this->normalizeOutput($row[self::COLUMN_INGREDIENT_UNIT_TYPE]),
                    'category' => '',
                ]));
            }
        }
        if ($currentRecipe) {
            $recipes->push($currentRecipe);
        }

        return collect($recipes);
    }

    protected function normalizeOutput(?string $string): ?string
    {
        return strtolower(trim($string));
    }
}
