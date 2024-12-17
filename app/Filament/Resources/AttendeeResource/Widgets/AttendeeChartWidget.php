<?php

namespace App\Filament\Resources\AttendeeResource\Widgets;

use App\Filament\Resources\AttendeeResource\Pages\ListAttendees;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class AttendeeChartWidget extends ChartWidget
{
    use InteractsWithPageTable;

    protected static ?string $heading = 'Attendee Signups';
    protected static ?string $maxHeight = '200px';
protected static ?string $pollingInterval = '5s';
        public ?string $filter = 'quarter'; // or null if no polling needed
    protected int|string|array $columnSpan = 'full';

    protected function getFilters(): ?array
    {
        return [
            'week' => 'Last Week',
            'month' => 'Last Month',
            'quarter' => 'Last Quarter',
            'year' => 'Last Year',
        ];
    }

    protected function getTablePage(): string
    {
        return ListAttendees::class;
    }

    protected function getData(): array
    {
        $filter = $this->filter;
        $query = $this->getPageTableQuery();
        $query->getQuery()->orders = [];
        match ($filter) {
            'week' => $data = Trend::query($query)
                ->between(
                    start: now()->startOfWeek(),
                    end: now()->endOfWeek(),
                )
                ->perDay()
                ->count(),
            'month' => $data = Trend::query($query)
                ->between(
                    start: now()->startOfMonth(),
                    end: now()->endOfMonth(),
                )
                ->perDay()
                ->count(),
            'quarter' => $data = Trend::query($query)
                ->between(
                    start: now()->startOfQuarter(),
                    end: now()->endOfQuarter(),
                )
                ->perMonth()
                ->count(),
            'year' => $data = Trend::query($query)
                ->between(
                    start: now()->startOfYear(),
                    end: now()->endOfYear(),
                )
                ->perMonth()
                ->count(),
        };

        return [
            'datasets' => [
                [
                    'label' => 'Attendee Signups',
                    'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn(TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
