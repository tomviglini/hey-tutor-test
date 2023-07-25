<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Support\Facades\DB;

use App\Models\Order;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use SoftDeletes, Authenticatable, Authorizable, HasFactory;

    protected $table = 'users';

    protected $fillable = [
        'name', 'email',
    ];

    protected $visible = [
        'id', 'name', 'email', 'orders', 'highestOrder'
    ];

    public function orders() {
        return $this->hasMany(Order::class);
    }

    public function highestOrder() {
        return $this->hasOne(Order::class)->whereIn('orders.id', function($query) {
            $query->select('orders.id')
                ->from('users')
                ->joinLateral(
                    Order::whereColumn('orders.user_id', 'users.id')
                        ->orderBy('total_amount', 'DESC')
                        ->limit(1),
                    'orders'
                );
        });
    }

    public function scopePurchasedAllProducts($query) {
        return $query->whereNotIn('users.id', function($query) {
            $query
                ->select('users.id')
                ->from('users')
                ->crossJoin('products')
                ->leftJoin('orders', function($join) {
                    $join
                        ->on('orders.user_id', '=', 'users.id')
                        ->on('orders.product_id', '=', 'products.id');
                })
                ->whereNull('orders.id');
        });
    }

    public function scopeHighestTotalSales($query) {
        $orderFilter = Order::select('user_id', DB::raw('SUM(total_amount) as total'))
            ->groupBy('user_id')
            ->orderBy('total', 'DESC')
            ->limit(1);

        return $query->joinSub($orderFilter, 'orders', function($join) {
            $join->on('users.id', '=', 'orders.user_id');
        });
    }
}
