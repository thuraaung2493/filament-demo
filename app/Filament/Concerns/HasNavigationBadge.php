<?php

declare(strict_types=1);

namespace App\Filament\Concerns;

trait HasNavigationBadge
{
    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() > 30 ? 'warning' : 'primary';
    }
}
