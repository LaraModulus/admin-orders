<?php
namespace LaraMod\AdminOrders\Models;

use LaraMod\AdminProducts\Models\Products;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrdersItems extends Model
{
    public $timestamps = true;
    protected $table = 'orders_items';

    use SoftDeletes;
    protected $guarded = ['id'];

    protected $dates = ['deleted_at'];

    public function product()
    {
        return $this->hasOne(Products::class, 'id', 'products_items_id');
    }

    public function order()
    {
        return $this->hasOne(Orders::class,'id', 'orders_id');
    }

}