<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'item',
        'description',
        'price',
        'quantity',
    ];

    public function orders()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
