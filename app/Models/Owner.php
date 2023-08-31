<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Owner extends Model
{
    use HasFactory;

    public function patients(): HasMany
    {
        return $this->hasMany(Patient::class);
    }
}
