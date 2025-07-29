<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Buyer extends User
{
    //
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
