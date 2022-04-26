<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;


    protected $primaryKey = 'ProductID';

    public function orderDetails()
    {
        return $this->belongsToMany("App\Models\OrderDetail", "Order Details");
    }
}
