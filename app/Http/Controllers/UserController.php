<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\User;

class UserController
{
    public function index()
    {
        // $users = User::purchasedAllProducts()->with('orders.product')->get();
        // $users = User::highestTotalSales()->with('orders.product')->get();
        $users = User::whereIn('id', [1, 2])->with('highestOrder')->get();
        return $users;

        DB::enableQueryLog();
        $users = User::whereIn('id', [1, 2])->with('highestOrder')->get();
        $query = DB::getQueryLog();
        echo '<pre>';
        print_r($query);
        die();
        return $users;
    }
}
