# e-commerce-platform

## Database Setup

1. Create the database and switch to it:

```sql
CREATE DATABASE test_platform;
USE test_platform;

```
2. Drop existing tables if they exist (optional):

```sql
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS category_product;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS categories;
```
3. Create tables:

```sql
CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE products (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description VARCHAR(255),
    price VARCHAR(100) NOT NULL UNIQUE,
    currency VARCHAR(255) NOT NULL DEFAULT 'USD'
);

CREATE TABLE categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE category_product (
    product_id BIGINT UNSIGNED NOT NULL,
    category_id BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (product_id, category_id),

    CONSTRAINT fk_catprod_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_catprod_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE ON UPDATE CASCADE
);
```

## For Running the Server call

```
php -S localhost:8000
```
