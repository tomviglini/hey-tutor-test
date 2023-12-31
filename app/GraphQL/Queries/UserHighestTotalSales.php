<?php

namespace App\GraphQL\Queries;

use App\Models\User;

final class UserHighestTotalSales
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        return User::highestTotalSales()->first();
    }
}
