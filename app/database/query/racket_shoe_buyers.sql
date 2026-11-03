SELECT
    c.name AS customer_name,
    oi.product_name,
    p.category_id
FROM customers c
INNER JOIN orders o
    ON o.customer_id = c.id
INNER JOIN order_items oi
    ON oi.order_id = o.id
INNER JOIN products p
    ON p.id = oi.product_id
WHERE p.category_id IN (1, 6)
  AND c.id != 1
ORDER BY p.category_id ASC, c.name ASC;