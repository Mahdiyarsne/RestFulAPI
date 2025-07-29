<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Seller extends User
{
    //
    public function products()
    {

        return $this->hasMany(Product::class);
    }
}
