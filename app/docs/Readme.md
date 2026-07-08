# PHP Modular MVC Full Coding Convention

---

# 1. Core Principles

- One file = one responsibility.
- Controllers must remain thin.
- Services contain business logic.
- Repositories are responsible for data access only.
- Views are responsible for presentation only.
- No SQL queries inside Controllers.
- No business logic inside Views.
- Strict separation between layers.
- Code must be modular, scalable, maintainable, and predictable.

---

# 2. Naming Convention

## Classes, Methods, Variables, and Constants

### Class Names

Use PascalCase.

Example:

```php
ProductController
OrderService
UserRepository
```

### Method Names

Use camelCase.

Example:

```php
getProductList()
createOrder()
findUserById()
```

### Variable Names

Use camelCase.

Example:

```php
$productList
$currentUser
$orderItems
```

### Constant Names

Use UPPER_SNAKE_CASE.

Example:

```php
MAX_UPLOAD_SIZE
DEFAULT_LANGUAGE
CACHE_LIFETIME
```

---

## Module Names

Module names must:

- Use lowercase letters only.
- Prefer singular nouns.

Examples:

```txt
admin
shop
website
booking
finance
```

---

## Folder Naming

### Architecture Folders

Use lowercase singular names.

Examples:

```txt
app
core
config
public
storage
common
```

### Collection Folders

Use lowercase plural names when the folder contains multiple items of the same type.

Examples:

```txt
modules
controllers
services
repositories
validators
views
assets
middleware
```

---

# 3. Route (Router) Rules

## URL Convention

URLs must represent resources and follow RESTful conventions.

### Resource URLs

Always use plural nouns for resources.

Examples:

```txt
/admin/products
/admin/categories
/admin/orders
/admin/users
```

### Route Definitions

```php
Router::get('/admin/products', 'ProductController@index');

Router::get('/admin/products/{id}', 'ProductController@show');

Router::post('/admin/products', 'ProductController@store');

Router::put('/admin/products/{id}', 'ProductController@update');

Router::delete('/admin/products/{id}', 'ProductController@destroy');
```

### RESTful Actions

| HTTP Method | URL                  | Action  |
| ----------- | -------------------- | ------- |
| GET         | /admin/products      | index   |
| GET         | /admin/products/{id} | show    |
| POST        | /admin/products      | store   |
| PUT         | /admin/products/{id} | update  |
| DELETE      | /admin/products/{id} | destroy |

---

# 4. Layer Responsibilities

## Controller

Responsibilities:

- Receive HTTP requests.
- Validate request flow.
- Call Services.
- Return Views or Responses.

Controllers should not:

- Execute SQL queries.
- Contain business logic.

---

## Service

Responsibilities:

- Contain all business logic.
- Coordinate repositories.
- Process application rules.

Services should not:

- Render HTML.
- Access HTTP directly.

---

## Repository

Responsibilities:

- Communicate with the database.
- Execute queries.
- Return data objects or arrays.

Repositories should not:

- Contain business rules.
- Handle HTTP requests.

---

## View

Responsibilities:

- Render UI only.
- Display prepared data.

Views should not:

- Execute SQL.
- Contain business logic.

---

# 5. General Rules

- Follow the Single Responsibility Principle (SRP).
- Keep methods small and focused.
- Avoid duplicated code (DRY).
- Prefer dependency injection over tight coupling.
- Write reusable and testable code.
- Keep naming explicit and meaningful.
- Maintain consistent project structure.

---

# 6. Database Convention

## Database Naming

Use lowercase and snake_case.

Examples:

```txt
badminton_shop
inventory_system
booking_management
```

---

## Table Naming

### Rules

- Use lowercase.
- Use plural nouns.
- Use snake_case.

Examples:

```txt
products
categories
brands
orders
order_items
users
roles
permissions
suppliers
purchases
transactions
```

---

## Column Naming

Use lowercase snake_case.

Examples:

```txt
product_name
category_id
created_at
updated_at
```

Do not use:

```txt
ProductName
productName
CategoryID
```

---

## Primary Key

Every table must contain:

```sql
id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
```

---

## Foreign Key Naming

Format:

```txt
{table_singular}_id
```

Examples:

```txt
category_id
brand_id
order_id
user_id
supplier_id
account_id
```

---

## Timestamp Columns

All business tables must contain:

```sql
created_at DATETIME NULL
updated_at DATETIME NULL
```

---

## Soft Delete

If soft delete is required:

```sql
deleted_at DATETIME NULL
```

---

## Status Column

Use:

```sql
status TINYINT DEFAULT 1
```

Convention:

```txt
1 = Active
0 = Inactive
```

---

## Boolean Fields

Use:

```sql
TINYINT(1)
```

Examples:

```sql
is_default TINYINT(1) DEFAULT 0
is_featured TINYINT(1) DEFAULT 0
```

---

## Monetary Fields

Never use:

```sql
FLOAT
DOUBLE
```

Always use:

```sql
DECIMAL(15,2)
```

Examples:

```sql
price DECIMAL(15,2)
cost_price DECIMAL(15,2)
sale_price DECIMAL(15,2)
amount DECIMAL(15,2)
```

---

## Quantity Fields

Use:

```sql
INT
```

Examples:

```sql
quantity INT DEFAULT 0
stock_quantity INT DEFAULT 0
```

---

## Text Fields

### Short Text

```sql
VARCHAR(255)
```

Examples:

```sql
name VARCHAR(255)
slug VARCHAR(255)
sku VARCHAR(255)
```

### Long Text

```sql
TEXT
```

Examples:

```sql
description TEXT
note TEXT
```

---

## Image Fields

Store image path only.

```sql
image VARCHAR(255)
thumbnail VARCHAR(255)
```

Do not store image binary data inside database.

---

## Enum Convention

Avoid:

```sql
ENUM
```

Prefer:

```sql
type VARCHAR(50)
status TINYINT
```

Or create lookup tables:

```txt
order_statuses
payment_methods
transaction_types
```

---

## Unique Constraints

Examples:

```sql
sku VARCHAR(100) UNIQUE
slug VARCHAR(255) UNIQUE
email VARCHAR(255) UNIQUE
```

---

## Index Convention

Create indexes on frequently searched columns.

Examples:

```sql
INDEX idx_name(name)
INDEX idx_slug(slug)
INDEX idx_category_id(category_id)
INDEX idx_created_at(created_at)
```

---

## Foreign Key Naming

Format:

```txt
fk_{table}_{reference}
```

Examples:

```txt
fk_products_category
fk_products_brand
fk_orders_user
fk_order_items_order
```

Example:

```sql
CONSTRAINT fk_products_category
FOREIGN KEY (category_id)
REFERENCES categories(id)
```

---

# 7. SQL Style Convention

## SQL Keywords

Always uppercase.

Example:

```sql
SELECT *
FROM products
WHERE status = 1
ORDER BY id DESC
```

---

## One Column Per Line

Example:

```sql
SELECT
    id,
    name,
    price,
    status
FROM products;
```

---

## Insert Style

```sql
INSERT INTO products (
    name,
    sku,
    price
)
VALUES (
    'Astrox 100ZZ',
    'AX100ZZ',
    5200000
);
```

---

# 8. Example Product Table

```sql
CREATE TABLE products (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    category_id BIGINT UNSIGNED NOT NULL,
    brand_id BIGINT UNSIGNED NULL,

    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    sku VARCHAR(100) UNIQUE,

    price DECIMAL(15,2) DEFAULT 0,
    stock_quantity INT DEFAULT 0,

    image VARCHAR(255) NULL,
    description TEXT NULL,

    status TINYINT DEFAULT 1,

    created_at DATETIME NULL,
    updated_at DATETIME NULL,

    INDEX idx_name(name),
    INDEX idx_slug(slug),
    INDEX idx_category_id(category_id),

    CONSTRAINT fk_products_category
        FOREIGN KEY (category_id)
        REFERENCES categories(id),

    CONSTRAINT fk_products_brand
        FOREIGN KEY (brand_id)
        REFERENCES brands(id)
);
```

---

# 9. Project Goal

The architecture must ensure:

- High maintainability.
- Easy onboarding for new developers.
- Clear separation of concerns.
- Consistent naming and structure.
- Scalability for future modules.
- Predictable code organization.
- Easy testing and deployment.
- Long-term project sustainability.

```

```
