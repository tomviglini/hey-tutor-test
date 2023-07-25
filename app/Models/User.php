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

    public function scopePurchasedAllProducts($query) {
        return $query->whereNotIn('users.id', function($subQuery) {
            $subQuery
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
        $orderFilter = DB::table('orders')
            ->select('user_id', DB::raw('SUM(total_amount) as total'))
            ->groupBy('user_id')
            ->orderBy('total', 'DESC')
            ->limit(1);

        return $query->joinSub($orderFilter, 'orders', function($join) {
            $join->on('users.id', '=', 'orders.user_id');
        });
    }
}
