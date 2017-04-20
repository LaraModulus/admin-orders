<?php
namespace LaraMod\AdminOrders\Models;

use LaraMod\AdminCore\Scopes\AdminCoreOrderByCreatedAtScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Orders extends Model
{
    public $timestamps = true;
    protected $table = 'orders';

    use SoftDeletes;
    protected $guarded = ['id'];

    protected $dates = ['deleted_at'];


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
        return $this->hasMany(OrdersItems::class,'order_id')->onlyTrashed();
    }

}