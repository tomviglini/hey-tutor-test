<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Product;

class Order extends Model
{
    use SoftDeletes;

    protected $table = 'orders';

    protected $fillable = [
        'user_id', 'product_id', 'quantity', 'total_amount'
    ];

    protected $visible = [
        'id', 'quantity', 'total_amount', 'product'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
