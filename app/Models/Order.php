<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $primaryKey = 'OrderID';
    public $timestamps = false;

    protected $fillable = [
        'CustomerID',
        'EmployeeID',
        'ShipVia',
        'Freight',
        'ShipName',
        'ShipAddress',
        'ShipCity',
        'ShipRegion',
        'ShipPostalCode',
        'ShipCountry',
    ];

    public function orderDetail()
    {
        return $this->hasMany("App\Models\OrderDetail", "OrderID");
    }
}
