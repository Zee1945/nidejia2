<?php

namespace App\Filament\Widgets;

use App\Models\Listing;
use App\Models\Transaction;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class StatsOverview extends BaseWidget
{
    private function getPercentage(int $from, int $to){
        return $to-$from/(($to+$from)/2)*100;
    }
    protected function getStats(): array
    {

        $newListing = Listing::whereMonth('created_at',Carbon::now()->month)->whereYear('created_at',Carbon::now()->year);
        $transaction = Transaction::whereStatus('approved')->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year);
        $prevTransaction = Transaction::whereStatus('approved')->whereMonth('created_at', Carbon::now()->subMonth()->month)->whereYear('created_at', Carbon::now()->subMonth()->year);
        $trans_percentage = $this->getPercentage($prevTransaction->count(), $transaction->count());
        $revenue_percentage = $this->getPercentage($prevTransaction->sum('total_price'), $transaction->sum('total_price'));
        $is_profit_trans = $trans_percentage>0?true:false;
        $is_profit_rev = $revenue_percentage>0?true:false;
        return [
            Stat::make('New Listing of The Month', $newListing->count()),
            Stat::make('Transaction of the Month', $transaction->count())
                ->description($trans_percentage.'%'.($is_profit_trans>0?'increased':'decreased'))
                ->descriptionIcon($is_profit_trans?'heroicon-m-arrow-trending-up': 'heroicon-m-arrow-trending-down')
                ->color($is_profit_trans?'success':'danger'),
            Stat::make('Revenue this Month', Number::currency($transaction->sum('total_price')) )
                ->description($trans_percentage.'%'.($is_profit_rev>0?'increased':'decreased'))
                ->descriptionIcon($is_profit_rev?'heroicon-m-arrow-trending-up': 'heroicon-m-arrow-trending-down')
                ->color($is_profit_rev?'success':'danger'),
            // Stat::make('Previous Transaction', $prevTransaction),
        ];
    }
}
