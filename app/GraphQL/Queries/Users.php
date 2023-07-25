<?php

namespace App\GraphQL\Queries;

use App\Models\User;

final class Users
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        return User::limit($args['limit'])->get();
    }
}
