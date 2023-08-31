<?php

declare(strict_types=1);

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use Illuminate\Contracts\Database\Eloquent\Builder;

final class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make()
                ->icon('heroicon-o-user-group')
                ->badge(fn () => User::query()->count()),

            'verified' => Tab::make()
                ->modifyQueryUsing(
                    fn (Builder $query) => $query->whereNotNull('email_verified_at')
                )
                ->icon('heroicon-o-check-badge')
                ->badge(fn () => User::query()->whereNotNull('email_verified_at')->count()),
        ];
    }
}
