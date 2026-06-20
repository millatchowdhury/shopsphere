-- Optional raw SQL reference seed data.
-- The primary seed path is database/seeders/EcommerceSeeder.php so IDs stay portable.

INSERT INTO site_settings (`key`, `value`, `created_at`, `updated_at`) VALUES
('website_title', 'ShopSphere', NOW(), NOW()),
('store_name', 'ShopSphere', NOW(), NOW()),
('store_email', 'support@shopsphere.test', NOW(), NOW()),
('store_phone', '+1 555 0100', NOW(), NOW()),
('store_address', '100 Commerce Street', NOW(), NOW()),
('currency_symbol', '$', NOW(), NOW());

INSERT INTO coupons (`code`, `type`, `value`, `minimum_order_amount`, `status`, `created_at`, `updated_at`) VALUES
('WELCOME10', 'percent', 10.00, 50.00, 1, NOW(), NOW());
