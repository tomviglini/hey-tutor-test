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
        return User::purchasedAllProducts()->get();
    }
}
