<?php

declare(strict_types=1);

namespace App\Filament\Resources\OwnerResource\Pages;

use App\Filament\Resources\OwnerResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateOwner extends CreateRecord
{
    protected static string $resource = OwnerResource::class;
}
