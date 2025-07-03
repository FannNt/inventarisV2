<?php

namespace App\Filament\Widgets;

use App\Models\Item;
use App\Models\Ruangan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class StatOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected static bool $isLazy = false;

    protected function getStats(): array
    {
        $expiredCount = $this->getExpiredItemsCount();
        $expiringSoonCount = $this->getExpiringSoonCount();
        $validCount = $this->getValidItemsCount();
        $totalCount = Item::count();


        return [
            // Critical - Expired Items
            $this->createExpiredStat($expiredCount),

            // Warning - Expiring Soon
            $this->createExpiringSoonStat($expiringSoonCount),

            // Success - Valid Items
            $this->createValidStat($validCount),

            // Info - Total Overview
            $this->createTotalStat($totalCount),
        ];
    }

    private function createExpiredStat(int $count): Stat
    {
        return Stat::make('Expired', $count)
            ->description($this->getExpiredDescription($count))
            ->icon('heroicon-o-x-circle')
            ->color('danger')
            ->url($this->getFilteredUrl('expired'))
            ->extraAttributes([
                'class' => 'cursor-pointer transition-all duration-200 hover:scale-105',
                'title' => 'Click to view expired items'
            ]);
    }

    private function createExpiringSoonStat(int $count): Stat
    {

        return Stat::make('Expiring Soon', $count)
            ->description($this->getExpiringSoonDescription($count))
            ->icon('heroicon-o-clock')
            ->color('warning')
            ->url($this->getFilteredUrl('expiring_soon'))
            ->extraAttributes([
                'class' => 'cursor-pointer transition-all duration-200 hover:scale-105',
                'title' => 'Items expiring within 3 months'
            ]);
    }

    private function createValidStat(int $count): Stat
    {
        return Stat::make('Valid', $count)
            ->description('Items in good condition')
            ->descriptionIcon('heroicon-m-check-badge')
            ->icon('heroicon-o-shield-check')
            ->color('success')
            ->url($this->getFilteredUrl('valid'))
            ->extraAttributes([
                'class' => 'cursor-pointer transition-all duration-200 hover:scale-105',
                'title' => 'Items with valid certification'
            ]);
    }

    private function createTotalStat(int $count): Stat
    {
        $roomCount = Ruangan::count();

        return Stat::make('Total Items', $count)
            ->description("Across {$roomCount} rooms")
            ->descriptionIcon('heroicon-m-building-office')
            ->icon('heroicon-o-cube')
            ->color('primary')
            ->url(route('filament.admin.resources.items.index'))
            ->extraAttributes([
                'class' => 'cursor-pointer transition-all duration-200 hover:scale-105',
                'title' => 'View all calibration items'
            ]);
    }

    private function getExpiredItemsCount(): int
    {
        return Item::where(function ($q) {
            $q->whereHas('latestCalibration', function ($query) {
                $query->where('expired_at', '<', now());
            })
                ->orWhere(function ($query) {
                    $query->whereDoesntHave('latestCalibration')
                        ->whereNotNull('expired_at')
                        ->where('expired_at', '<', now());
                });
        })->count();
    }

    private function getExpiringSoonCount(): int
    {
        return Item::where(function ($q) {
            $q->whereHas('latestCalibration', function ($query) {
                $query->whereBetween('expired_at', [now(), now()->addMonths(3)]);
            })
                ->orWhere(function ($query) {
                    $query->whereDoesntHave('latestCalibration')
                        ->whereBetween('expired_at', [now(), now()->addMonths(3)]);
                });
        })->count();

    }

    private function getValidItemsCount(): int
    {
        return Item::where(function ($q) {
            $q->whereHas('latestCalibration', function ($query) {
                $query->where('expired_at', '>', now()->addMonths(3));
            })
                ->orWhere(function ($query) {
                    $query->whereDoesntHave('latestCalibration')
                        ->where(function ($subQuery) {
                            $subQuery->where('expired_at', '>', now()->addMonths(3))
                                ->orWhereNull('expired_at');
                        });
                });
        })->count();

    }


    private function calculatePercentageChange(array $trend): float
    {
        if (count($trend) < 2) return 0;

        // Compare last 3 days vs previous 3 days for more stable trends
        $recent = array_slice($trend, -3);
        $previous = array_slice($trend, -6, 3);

        $recentSum = array_sum($recent);
        $previousSum = array_sum($previous);

        if ($previousSum == 0) return $recentSum > 0 ? 100 : 0;

        return round((($recentSum - $previousSum) / $previousSum) * 100, 1);
    }

    private function getExpiredDescription(int $count): string
    {
        if ($count === 0) return 'No expired items! 🎉';

        $urgency = $count > 10 ? 'Critical attention needed' : 'Needs attention';

        return "{$urgency}";
    }

    private function getExpiringSoonDescription(int $count): string
    {
        if ($count === 0) return 'No items expiring soon';

        $timeframe = 'Next 3 months';

        return "{$timeframe}";
    }

    private function getFilteredUrl(string $filter): string
    {
        return route('filament.admin.resources.items.index', [
            'tableFilters' => [
                'expiration_status' => ['value' => $filter]
            ]
        ]);
    }

    public function getColumns(): int
    {
        return 4;
    }

    protected function getViewData(): array
    {
        return array_merge(parent::getViewData(), [
            'pollingInterval' => '30s', // Auto-refresh every 30 seconds
        ]);
    }
}
