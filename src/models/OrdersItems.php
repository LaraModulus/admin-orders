<?php

namespace LaraMod\Admin\Orders\Models;

use LaraMod\Admin\Core\Traits\HelpersTrait;
use LaraMod\Admin\Products\Models\Products;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrdersItems extends Model
{
    public $timestamps = false;
    protected $table = 'orders_items';

    use SoftDeletes, HelpersTrait;
    protected $guarded = ['id'];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'qty'    => 'double',
        'price'  => 'double',
        'weight' => 'double',
    ];

    protected $fillable = [
        'product_name',
        'product_id',
        'price',
        'qty',
        'order_id',
        'weight',
        'selected_options',
    ];

    public function product()
    {
        return $this->hasOne(Products::class, 'id', 'product_id');
    }

    public function order()
    {
        return $this->hasOne(Orders::class, 'id', 'order_id');
    }

}