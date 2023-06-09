<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class photos extends Model
{
    use HasFactory;

    public function recipe(): BelongsTo
    {
        return $this->belongsTo(recipe::class);
    }
}
