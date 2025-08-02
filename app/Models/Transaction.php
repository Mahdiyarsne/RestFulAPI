<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    //

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'quantity',
        'buyer_id',
        'product_id'
    ];


    public function buyer(): BelongsTo
    {

        return $this->belongsTo(Buyer::class);
    }


    public function product()
    {

        return $this->belongsTo(Product::class);
    }
}
