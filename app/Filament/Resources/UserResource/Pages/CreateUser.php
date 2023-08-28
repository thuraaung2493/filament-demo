<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard\Step;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\CreateRecord\Concerns\HasWizard;

class CreateUser extends CreateRecord
{
    use HasWizard;

    protected static string $resource = UserResource::class;

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'User registered';
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        unset($data['password_confirmation']);

        return $data;
    }

    protected function getSteps(): array
    {
        return [
            Step::make('Name')
                ->description('Give a user name')
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->live()
                ]),
            Step::make('Email')
                ->description('Give a user email address')
                ->schema([
                    TextInput::make('email')
                        ->email()
                        ->required(),

                    TextInput::make('password')
                        ->password()
                        ->confirmed()
                        ->required(),

                    TextInput::make('password_confirmation')
                        ->password()
                        ->required(),
                ])
        ];
    }
}
