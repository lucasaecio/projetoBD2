<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $table = 'Order Details';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'OrderID',
        "ProductID",
        "UnitPrice",
        "Quantity",
        "Discount"
    ];



    public function products()
    {
        return $this->belongsToMany(
            Product::class,
            'Order Details',
            'OrderID',
            'ProductID'
        );
    }
}
