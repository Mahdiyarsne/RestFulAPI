<?php

namespace App\Models;

use App\Scopes\BuyerScope;
use Illuminate\Database\Eloquent\Model;

class Buyer extends User
{
    //

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
