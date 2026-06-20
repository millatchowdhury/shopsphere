# ShopSphere Laravel 12 Ecommerce

ShopSphere is a readable Laravel 12 ecommerce website built with PHP 8.3, MySQL, Bootstrap 5, and Blade. It includes a responsive storefront, customer auth, cart, COD checkout, order history, invoices, and a protected admin panel.

## Main Features

- Storefront home page with hero slider, categories, featured products, new arrivals, product search, cart, checkout, about, contact, and live chat placeholder.
- Customer registration/login, dashboard, order history, invoice, and tracking status.
- Admin panel for dashboard statistics, products, categories, brands, orders, customers, coupons, banners, and site settings.
- Migrations, Eloquent models, relationships, factories, seeders, repository/service layers, validation, CSRF, auth middleware, admin middleware, and policies.

## Installation

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Create a MySQL database named `shopsphere` first, or update these `.env` values:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=shopsphere
DB_USERNAME=root
DB_PASSWORD=
```

Demo accounts after seeding:

- Admin: `admin@example.com` / `password`
- Customer: `customer@example.com` / `password`

## Development Notes

- Editable CSS lives in `public/css/site.css`.
- Editable JavaScript lives in `public/js/site.js`.
- Storefront Blade files live in `resources/views/frontend`.
- Admin Blade files live in `resources/views/admin`.
- Cart and order business logic live in `app/Services`.
- Product querying lives behind `App\Repositories\Contracts\ProductRepositoryInterface`.

## Database Schema Diagram

```mermaid
erDiagram
    USERS ||--o{ ORDERS : places
    CATEGORIES ||--o{ PRODUCTS : contains
    BRANDS ||--o{ PRODUCTS : makes
    PRODUCTS ||--o{ PRODUCT_IMAGES : has
    PRODUCTS ||--o{ ORDER_ITEMS : sold_as
    ORDERS ||--o{ ORDER_ITEMS : contains
    COUPONS ||--o{ ORDERS : discounts

    USERS {
        bigint id PK
        string name
        string email
        string password
        string role
        string phone
        text address
    }

    CATEGORIES {
        bigint id PK
        string name
        string slug
        text description
        boolean status
    }

    BRANDS {
        bigint id PK
        string name
        string slug
        string logo
        boolean status
    }

    PRODUCTS {
        bigint id PK
        bigint category_id FK
        bigint brand_id FK
        string name
        string slug
        decimal price
        decimal discount_price
        integer stock_quantity
        string sku
        text description
        boolean status
    }

    PRODUCT_IMAGES {
        bigint id PK
        bigint product_id FK
        string image_path
        boolean is_primary
        integer sort_order
    }

    ORDERS {
        bigint id PK
        string order_number
        bigint user_id FK
        bigint coupon_id FK
        decimal subtotal
        decimal discount
        decimal shipping
        decimal total
        string status
        string payment_method
    }

    ORDER_ITEMS {
        bigint id PK
        bigint order_id FK
        bigint product_id FK
        string product_name
        string sku
        decimal unit_price
        integer quantity
        decimal line_total
    }

    COUPONS {
        bigint id PK
        string code
        string type
        decimal value
        boolean status
    }
```

## Production Checklist

- Set `APP_ENV=production` and `APP_DEBUG=false`.
- Configure a real mailer and queue worker if contact/support notifications are added.
- Replace demo image URLs with hosted product assets or Laravel storage uploads.
- Put the app behind HTTPS and configure secure session cookies.
- Run `php artisan config:cache`, `route:cache`, and `view:cache` during deployment.
