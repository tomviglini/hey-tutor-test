<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController
{
    public function index()
    {
        $users = User::purchasedAllProducts()->with('orders.product')->get();
        return $users;
    }
}
