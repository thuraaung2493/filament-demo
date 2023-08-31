<?php

declare(strict_types=1);

namespace App\Filament\Resources\OwnerResource\Pages;

use App\Filament\Resources\OwnerResource;
use Filament\Resources\Pages\EditRecord;

final class EditOwner extends EditRecord
{
    protected static string $resource = OwnerResource::class;
}
