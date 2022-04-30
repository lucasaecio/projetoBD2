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

    public function Order()
    {
        return $this->belongsToMany(
            Order::class,
            'Orders',
            'OrderID'
        );
    }


    public function product()
    {
        return $this->belongsTo(Product::class, "ProductID");
    }
}
