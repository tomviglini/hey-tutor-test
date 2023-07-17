<?php

namespace App\GraphQL\Queries;

use App\Models\User;

final class UsersPurchasedAllproducts
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        // TODO: only include fields based on graphQL query
        return User::purchasedAllProducts()->with('orders.product')->get();
    }
}
