<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    //

    protected $fillable = [
        'quantity',
        'buyer_id',
        'product_id'
    ];


    public function buyer(): BelongsTo
    {

        return $this->belongsTo(Buyer::class);
    }


    public function product(): BelongsTo
    {

        return $this->belongsTo(Product::class);
    }
}
