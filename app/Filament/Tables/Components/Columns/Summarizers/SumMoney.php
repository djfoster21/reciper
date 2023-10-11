<?php

namespace App\Filament\Tables\Components\Columns\Summarizers;

use App\Services\PriceService;
use Brick\Money\AbstractMoney;
use Exception;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Schema;

class SumMoney extends Summarizer
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->numeric();
    }

    public function summarize(Builder $query, string $attribute): int|float|array|null
    {
        $column = $this->getColumn();
        if (Schema::hasColumn($this->getQuery()->getModel(), $column->getName())) {
            throw new Exception("The [{$column->getName()}] column must be an Attribute.");
        }

        $limit = $this->getQuery()->getQuery()->limit;
        $offset = $this->getQuery()->getQuery()->offset;

        $result = $this->getQuery()
            ->get()
            ->skip($offset)
            ->take($limit)
            ->pluck($attribute)
            ->reduce(function (AbstractMoney $carry, $item) {
                if ($item) {
                    return $carry->plus($item);
                }

                return $carry;
            }, PriceService::fromFloat(0.00));

        return PriceService::toFloat($result);
    }
}
