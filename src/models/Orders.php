<?php

namespace LaraMod\Admin\Orders\Models;

use LaraMod\Admin\Core\Scopes\AdminCoreOrderByCreatedAtScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use LaraMod\Admin\Core\Traits\HelpersTrait;

class Orders extends Model
{
    public $timestamps = true;
    protected $table = 'orders';

    use SoftDeletes, HelpersTrait;
    protected $guarded = ['id'];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'invoice_data' => 'object',
    ];

    protected $fillable = [
        'names',
        'phone',
        'email',
        'city',
        'country',
        'address',
        'note',
        'admin_note',
        'seen',
        'status',
        'payment_method',
        'invoice_data',
    ];


    protected function bootIfNotBooted()
    {
        parent::boot();
        static::addGlobalScope(new AdminCoreOrderByCreatedAtScope());
    }

    public function items()
    {
        return $this->hasMany(OrdersItems::class, 'order_id');
    }

    public function history()
    {
        return $this->hasMany(OrdersItems::class, 'order_id')->onlyTrashed();
    }

}