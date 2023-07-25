## Setup

```bash
docker-compose up
```

## Run Migration

```bash
docker-compose exec api php artisan migrate
```

## Run Seeds

```bash
docker-compose exec api php artisan db:seed
```

## Query to Retrieve Users and Their Most Expensive Order:

```sql
SELECT
    u.*, o.id order_id, o.total_amount
FROM
    users u
INNER JOIN LATERAL (
    SELECT
        *
    FROM
        orders o
    WHERE
        o.user_id = u.id
    ORDER BY
        o.total_amount DESC
    LIMIT
        1
) o
```

## Query to Retrieve Users Who Have Purchased All Products:

```sql
SELECT
    *
FROM
    users
WHERE
    users.id NOT IN(
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
)
```

## Query to Retrieve the User with the Highest Total Sales:

```sql
SELECT
    *
FROM
    users
INNER JOIN (
    SELECT
        user_id,
        SUM(total_amount) AS total
    FROM
        orders
    GROUP BY
        user_id
    ORDER BY
        total DESC
    LIMIT
        1
) AS orders ON users.id = orders.user_id
WHERE
    users.deleted_at IS NULL
```

## GraphQL http://localhost/graphiql

```graphql
{
    UsersPurchasedAllproducts {
        id
        name
        email
        orders {
            id
            quantity
            total_amount
            product {
                id
                name
                price
            }
        }
    }
}
```

```graphql
{
    UserHighestTotalSales {
        id
        name
        email
        orders {
            id
            quantity
            total_amount
            product {
                id
                name
                price
            }
        }
    }
}
```
