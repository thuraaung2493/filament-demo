<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Concerns\HasNavigationBadge;
use App\Filament\Resources\RoleResource\Pages;
use App\Tables\Columns\PermissionsColumn;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Spatie\Permission\Models\Role;

final class RoleResource extends Resource
{
    use HasNavigationBadge;

    protected static ?string $model = Role::class;

    protected static ?string $navigationGroup = 'Admin Management';
    // protected static ?string $navigationIcon = 'heroicon-o-shield-check';
    // protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->columnSpanFull(),

                Forms\Components\Select::make('permissions')
                    ->relationship(name: 'permissions', titleAttribute: 'name')
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->columnSpanFull()
                    ->required()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->unique()
                            ->maxLength(255)
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('#')->rowIndex(),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('permissions.name')
                    ->default('*')
                    ->badge(),
                // PermissionsColumn::make('permissions')
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
