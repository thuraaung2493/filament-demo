<?php

declare(strict_types=1);

namespace App\Filament\Enums;

use Filament\Support\Contracts\HasLabel;

enum PatientType: string implements HasLabel
{
    case Dog = 'dog';
    case Cat = 'cat';
    case Rabbit = 'rabbit';

    public function getLabel(): ?string
    {
        return $this->name;
    }
}
