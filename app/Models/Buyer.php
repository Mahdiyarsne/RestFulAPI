<?php

namespace App\Models;

use App\Scopes\BuyerScope;
use App\Transformers\BuyerTransformer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Buyer extends User
{
    //
    public $transformer = BuyerTransformer::class;

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
