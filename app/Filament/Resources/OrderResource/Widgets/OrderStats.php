<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class OrderStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('New Orders', Order::query()->where('status', 'new')->count()),
            Stat::make('Order Processing', Order::query()->where('status', 'processing')->count()),
            Stat::make('Order Delivered ', Order::query()->where('status', 'Delivered')->count()),
            Stat::make('Total Price for Delivered Orders', Number::currency(Order::query()
            ->where('status', 'delivered')->sum('total'), 'EGP')),
        ];
    }
}
