<?php

namespace App\Filament\Resources\AttendeeResource\Widgets;

use App\Filament\Resources\AttendeeResource\Pages\ListAttendees;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AttendeesStatsWidget extends BaseWidget
{
    use InteractsWithPageTable;

    protected function getTablePage(): string
    {
        return ListAttendees::class;
    }

    protected function getColumns(): int
    {
        return 2;
    }

    protected function getStats(): array
    {
        $query = $this->getPageTableQuery();

        return [
            Stat::make('Attendees Count', $query->count())
                ->icon('heroicon-o-user-group')
                ->description('Total number of attendees')
                ->chart([147, 216, 394, 486])
                ->chartColor('success'),
            Stat::make('Total Revenue', $query->sum('ticket_cost'))
                ->icon('heroicon-o-currency-dollar')
                ->description('Total revenue from ticket sales'),
        ];
    }
}
