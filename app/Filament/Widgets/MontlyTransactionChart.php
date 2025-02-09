<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Contracts\Support\Htmlable;

class MontlyTransactionChart extends ChartWidget
{
    protected static ?int $sort = 2;
    protected static ?string $heading = 'Montly Transaction';

    protected function getData(): array
    {
        $trend = Trend::model(Transaction::class)
        ->between(
            start: now()->startOfMonth(),
            end: now()->endOfMonth(),
        )
            ->perDay()
            ->count();
        return [
            'datasets' => [
                [
                    'label' => 'Transaction created',
                    'data' => $trend->map(fn(TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $trend->map(fn(TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
    public function getDescription(): string|Htmlable|null
    {
        return 'The number of transaction created per Month.';
    }


}
