<?php

declare(strict_types=1);

namespace App\Tables\Columns;

use Filament\Tables\Columns\Column;

final class PermissionsColumn extends Column
{
    protected string $view = 'tables.columns.permissions-column';

    protected bool | Closure $isBadge = true;
}
