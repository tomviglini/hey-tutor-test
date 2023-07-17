<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Lumen\Auth\Authorizable;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use SoftDeletes, Authenticatable, Authorizable, HasFactory;

    protected $table = 'users';

    protected $fillable = [
        'name', 'email',
    ];

    protected $visible = [
        'id', 'name', 'email', 'orders'
    ];

    public function orders() {
        return $this->hasMany(Order::class);
    }

    public function scopePurchasedAllProducts($query) {
        // IMPROVEMENT: move into queryBuilder
        return $query->whereRaw('users.id NOT IN(
            SELECT
                u.id id
            FROM
                users u
            JOIN
                products p
            LEFT JOIN
                orders o ON o.user_id = u.id AND o.product_id = p.id
            WHERE
                o.id IS NULL
        )');
    }

    public function scopeHighestTotalSales($query) {
        // IMPROVEMENT: move into queryBuilder
        $users = DB::select('
            SELECT
                user_id, SUM(total_amount) total
            FROM
                orders
            GROUP BY
                user_id
            ORDER BY
                total DESC
            LIMIT
                10;
        ');

        $ids = [];

        foreach($users as $user) {
            $ids[] = $user->user_id;
        }

        $query->whereIn('id', $ids);
        return $query;
    }
}
