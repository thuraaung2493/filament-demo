<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Concerns\HasNavigationBadge;
use App\Filament\Resources\AdminResource\Pages;
use App\Models\Admin;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Phpsa\FilamentPasswordReveal\Password;

final class AdminResource extends Resource
{
    use HasNavigationBadge;

    protected static ?string $model = Admin::class;

    protected static ?string $navigationGroup = 'Admin Management';
    // protected static ?string $navigationIcon = 'heroicon-o-users';
    // protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Password::make('password')
                    ->password()
                    ->same('passwordConfirmation')
                    ->maxLength(255)
                    ->required(fn (string $operation): bool => 'create' === $operation)
                    ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                    ->dehydrated(fn (?string $state): bool => \filled($state)),
                Password::make('passwordConfirmation')
                    ->password()
                    ->required(fn (Get $get): bool => \filled($get('password')))
                    ->dehydrated(\false),
                Forms\Components\Select::make('roles')
                    ->required()
                    ->relationship(name: 'roles', titleAttribute: 'name')
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('permissions')
                            ->relationship(name: 'permissions', titleAttribute: 'name')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->required()
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('#')->rowIndex(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->searchable()
                    ->badge(),
                Tables\Columns\TextColumn::make('roles.permissions.name')
                    ->searchable()
                    ->default('*')
                    ->badge(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListAdmins::route('/'),
            'create' => Pages\CreateAdmin::route('/create'),
            'edit' => Pages\EditAdmin::route('/{record}/edit'),
        ];
    }
}
