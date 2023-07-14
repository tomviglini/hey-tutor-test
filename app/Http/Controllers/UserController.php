<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController
{
    public function index()
    {
        $users = User::where('id', 1)->get();

        // Query to Retrieve Users and Their Most Expensive Order:

        // SELECT
        //     u.*, o.id order_id, o.total_amount
        // FROM
        //     users u
        // INNER JOIN LATERAL (
        //     SELECT
        //         *
        //     FROM
        //         orders o
        //     WHERE
        //         o.user_id = u.id
        //     ORDER BY
        //         o.total_amount DESC
        //     LIMIT
        //         5
        // ) o


        // Query to Retrieve Users Who Have Purchased All Products:

        // SELECT
        //     *
        // FROM
        //     users
        // WHERE
        //     users.id NOT IN(
        //     SELECT
        //         u.id id
        //     FROM
        //         users u
        //     JOIN
        //         products p
        //     LEFT JOIN
        //         orders o ON o.user_id = u.id AND o.product_id = p.id
        //     WHERE
        //         o.id IS NULL
        // )


        // Query to Retrieve Users with Highest Total Sales:

        // SELECT
        //     user_id, SUM(total_amount) total
        // FROM
        //     orders
        // GROUP BY
        //     user_id
        // ORDER BY
        //     total DESC
        // LIMIT
        //     10;


        return $users;
    }
}
