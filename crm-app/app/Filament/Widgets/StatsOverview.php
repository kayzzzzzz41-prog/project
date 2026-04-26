<?php

namespace App\Filament\Widgets;

use App\Models\Contract;
use App\Models\KaspiPayment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalContracts  = Contract::count();
        $paidContracts   = Contract::where('is_paid', true)->count();
        $unpaidContracts = Contract::where('is_paid', false)->count();
        $totalSum        = Contract::sum('amount');
        $paidSum         = Contract::where('is_paid', true)->sum('amount');
        $totalPayments   = KaspiPayment::where('command', 'pay')
                            ->where('result', 0)->count();

        return [
            Stat::make('Всего договоров', $totalContracts)
                ->description('Все договоры в системе')
                ->color('primary'),

            Stat::make('Оплачено', $paidContracts)
                ->description('Оплаченных договоров')
                ->color('success'),

            Stat::make('Не оплачено', $unpaidContracts)
                ->description('Ожидают оплаты')
                ->color('danger'),

            Stat::make('Общая сумма', number_format($totalSum, 0, '.', ' ') . ' ₸')
                ->description('Сумма всех договоров')
                ->color('warning'),

            Stat::make('Оплачено на сумму', number_format($paidSum, 0, '.', ' ') . ' ₸')
                ->description('Через Kaspi')
                ->color('success'),

            Stat::make('Платежей Kaspi', $totalPayments)
                ->description('Успешных транзакций')
                ->color('info'),
        ];
    }
}