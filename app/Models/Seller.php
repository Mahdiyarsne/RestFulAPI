<?php

namespace App\Models;

use App\Transformers\SellerTransformer;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Seller extends User
{
    public $transformer = SellerTransformer::class;
    //
    public function products(): HasMany
    {

        return $this->hasMany(Product::class);
    }
}
