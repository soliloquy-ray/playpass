# Database & Models Blueprint

## Overview
This document describes the database schema and model structure for the PlayPass application. The system is built using CodeIgniter 4 and includes user management, product catalog, orders, vouchers, points/rewards system, and content management.

---

## Database Schema

### Core Tables

#### 1. `users`
User accounts with authentication and referral system.

| Column | Type | Description |
|--------|------|-------------|
| `id` | INT(11) UNSIGNED | Primary key, auto-increment |
| `email` | VARCHAR(255) | Unique email address |
| `password_hash` | VARCHAR(255) | Hashed password |
| `first_name` | VARCHAR(100) | User's first name |
| `last_name` | VARCHAR(100) | User's last name |
| `role` | ENUM('admin', 'customer') | User role, default: 'customer' |
| `my_referral_code` | VARCHAR(20) | Unique referral code for this user |
| `referred_by_user_id` | INT UNSIGNED | Foreign key to `users.id` (who referred this user) |
| `current_points_balance` | INT | Cached points balance (source of truth is `point_ledger`) |
| `created_at` | DATETIME | Creation timestamp |
| `updated_at` | DATETIME | Last update timestamp |

**Indexes:**
- Primary key: `id`
- Unique: `email`
- Unique: `my_referral_code`

**Relationships:**
- Self-referential: `referred_by_user_id` → `users.id`
- One-to-many: `users.id` → `orders.user_id`
- One-to-many: `users.id` → `point_ledger.user_id`

---

#### 2. `point_ledger`
Transaction log for all points movements (the "bank statement" for points).

| Column | Type | Description |
|--------|------|-------------|
| `id` | BIGINT UNSIGNED | Primary key, auto-increment |
| `user_id` | INT UNSIGNED | Foreign key to `users.id` |
| `amount` | INT(11) | Points amount (positive = earn, negative = spend) |
| `transaction_type` | ENUM | Type: 'purchase_reward', 'redemption', 'referral_bonus', 'adjustment' |
| `reference_id` | VARCHAR(100) | Reference (e.g., Order ID or admin note) |
| `created_at` | DATETIME | Transaction timestamp |

**Indexes:**
- Primary key: `id`
- Foreign key: `user_id` → `users.id` (CASCADE on delete/update)

---

#### 3. `brands`
Brand/company information.

| Column | Type | Description |
|--------|------|-------------|
| `id` | INT UNSIGNED | Primary key, auto-increment |
| `name` | VARCHAR(100) | Brand name |
| `logo` | VARCHAR(255) | Logo URL or file path |
| `is_enabled` | BOOLEAN | Active status, default: true |
| `created_at` | DATETIME | Creation timestamp |
| `updated_at` | DATETIME | Last update timestamp |

**Indexes:**
- Primary key: `id`

**Relationships:**
- One-to-many: `brands.id` → `products.brand_id`

---

#### 4. `products`
Product catalog with pricing, visuals, and integration fields.

| Column | Type | Description |
|--------|------|-------------|
| `id` | INT UNSIGNED | Primary key, auto-increment |
| `brand_id` | INT UNSIGNED | Foreign key to `brands.id` (nullable) |
| `sku` | VARCHAR(50) | Unique SKU |
| `name` | VARCHAR(255) | Product name |
| `description` | TEXT | Product description |
| `price` | DECIMAL(10,2) | Product price |
| `bg_color` | VARCHAR(7) | Background color hex, default: '#1a1a1a' |
| `thumbnail_url` | VARCHAR(255) | Thumbnail image URL |
| `badge_label` | VARCHAR(50) | Badge text (e.g., "NEW", "HOT") |
| `logo_url` | VARCHAR(255) | Product logo URL |
| `maya_product_code` | VARCHAR(100) | Maya payment integration code |
| `points_to_earn` | INT | Points awarded on purchase, default: 0 |
| `is_bundle` | BOOLEAN | Is this a bundle product, default: false |
| `is_featured` | BOOLEAN | Featured product flag, default: false |
| `is_new` | BOOLEAN | New product flag, default: false |
| `sort_order` | INT | Display order, default: 0 |
| `is_active` | BOOLEAN | Active status, default: true |
| `created_at` | DATETIME | Creation timestamp |
| `updated_at` | DATETIME | Last update timestamp |

**Indexes:**
- Primary key: `id`
- Unique: `sku`
- Foreign key: `brand_id` → `brands.id` (CASCADE on delete, SET NULL on update)

**Relationships:**
- Many-to-one: `products.brand_id` → `brands.id`
- One-to-many: `products.id` → `bundle_items.parent_product_id`
- One-to-many: `products.id` → `bundle_items.child_product_id`
- One-to-many: `products.id` → `order_items.product_id`
- One-to-many: `products.id` → `voucher_applicability.product_id`

---

#### 5. `bundle_items`
Defines bundle products and their constituent items.

| Column | Type | Description |
|--------|------|-------------|
| `id` | INT UNSIGNED | Primary key, auto-increment |
| `parent_product_id` | INT UNSIGNED | Foreign key to `products.id` (the bundle) |
| `child_product_id` | INT UNSIGNED | Foreign key to `products.id` (the item) |
| `quantity` | INT | Quantity of child in bundle, default: 1 |

**Indexes:**
- Primary key: `id`
- Foreign key: `parent_product_id` → `products.id` (CASCADE)
- Foreign key: `child_product_id` → `products.id` (CASCADE)

---

### Voucher System

#### 6. `voucher_campaigns`
Voucher campaign rules and configuration.

| Column | Type | Description |
|--------|------|-------------|
| `id` | INT UNSIGNED | Primary key, auto-increment |
| `name` | VARCHAR(100) | Campaign name |
| `description` | TEXT | Campaign description |
| `code_type` | ENUM | 'universal' or 'unique_batch' |
| `discount_type` | ENUM | 'fixed_amount' or 'percentage' |
| `discount_value` | DECIMAL(10,2) | Discount amount or percentage (e.g., 100.00 or 0.15 for 15%) |
| `min_spend_amount` | DECIMAL(10,2) | Minimum spend required, default: 0 |
| `max_discount_amount` | DECIMAL(10,2) | Maximum discount cap (for % discounts) |
| `usage_limit_per_user` | INT | Max uses per user, default: 1 |
| `total_usage_limit` | INT | Total usage limit (null = infinite) |
| `is_stackable` | BOOLEAN | Can be used with other vouchers, default: false |
| `start_date` | DATETIME | Campaign start date |
| `end_date` | DATETIME | Campaign end date |
| `created_at` | DATETIME | Creation timestamp |

**Indexes:**
- Primary key: `id`

**Relationships:**
- One-to-many: `voucher_campaigns.id` → `voucher_codes.campaign_id`
- One-to-many: `voucher_campaigns.id` → `voucher_applicability.campaign_id`

---

#### 7. `voucher_applicability`
Defines which products a voucher campaign applies to.

| Column | Type | Description |
|--------|------|-------------|
| `id` | INT UNSIGNED | Primary key, auto-increment |
| `campaign_id` | INT UNSIGNED | Foreign key to `voucher_campaigns.id` |
| `product_id` | INT UNSIGNED | Foreign key to `products.id` |

**Indexes:**
- Primary key: `id`
- Foreign key: `campaign_id` → `voucher_campaigns.id` (CASCADE)
- Foreign key: `product_id` → `products.id` (CASCADE)

---

#### 8. `voucher_codes`
Actual voucher codes that users can redeem.

| Column | Type | Description |
|--------|------|-------------|
| `id` | BIGINT UNSIGNED | Primary key, auto-increment |
| `campaign_id` | INT UNSIGNED | Foreign key to `voucher_campaigns.id` |
| `code` | VARCHAR(50) | The actual code string (e.g., "WELCOME20") |
| `is_redeemed` | BOOLEAN | Redemption status, default: false |
| `redeemed_at` | DATETIME | Redemption timestamp |
| `redeemed_by_user_id` | INT UNSIGNED | Foreign key to `users.id` (who redeemed it) |

**Indexes:**
- Primary key: `id`
- Unique: `code`
- Foreign key: `campaign_id` → `voucher_campaigns.id` (CASCADE)

**Relationships:**
- Many-to-one: `voucher_codes.campaign_id` → `voucher_campaigns.id`
- One-to-many: `voucher_codes.id` → `order_applied_vouchers.voucher_code_id`

---

### Order System

#### 9. `orders`
Order header with payment and fulfillment status.

| Column | Type | Description |
|--------|------|-------------|
| `id` | BIGINT UNSIGNED | Primary key, auto-increment |
| `user_id` | INT UNSIGNED | Foreign key to `users.id` |
| `subtotal` | DECIMAL(10,2) | Subtotal before discounts |
| `discount_total` | DECIMAL(10,2) | Total discount amount, default: 0 |
| `points_redeemed_value` | DECIMAL(10,2) | Points redeemed value, default: 0 |
| `grand_total` | DECIMAL(10,2) | Final total after all adjustments |
| `payment_status` | ENUM | 'pending', 'paid', 'failed', 'refunded', default: 'pending' |
| `fulfillment_status` | ENUM | 'pending', 'sent', 'failed', default: 'pending' |
| `maya_checkout_id` | VARCHAR(255) | Maya payment checkout ID |
| `maya_reference_number` | VARCHAR(255) | Maya payment reference number |
| `created_at` | DATETIME | Creation timestamp |
| `updated_at` | DATETIME | Last update timestamp |

**Indexes:**
- Primary key: `id`
- Foreign key: `user_id` → `users.id` (CASCADE)

**Relationships:**
- Many-to-one: `orders.user_id` → `users.id`
- One-to-many: `orders.id` → `order_items.order_id`
- One-to-many: `orders.id` → `order_applied_vouchers.order_id`
- One-to-many: `orders.id` → `order_price_adjustments.order_id`

---

#### 10. `order_items`
Order line items with snapshot pricing.

| Column | Type | Description |
|--------|------|-------------|
| `id` | BIGINT UNSIGNED | Primary key, auto-increment |
| `order_id` | BIGINT UNSIGNED | Foreign key to `orders.id` |
| `product_id` | INT UNSIGNED | Foreign key to `products.id` |
| `quantity` | INT | Quantity purchased, default: 1 |
| `price_at_purchase` | DECIMAL(10,2) | Snapshot of product price at time of purchase |
| `total` | DECIMAL(10,2) | Line total (quantity × price_at_purchase) |

**Indexes:**
- Primary key: `id`
- Foreign key: `order_id` → `orders.id` (CASCADE)

**Relationships:**
- Many-to-one: `order_items.order_id` → `orders.id`
- Many-to-one: `order_items.product_id` → `products.id`

---

#### 11. `order_applied_vouchers`
Tracks which vouchers were applied to each order.

| Column | Type | Description |
|--------|------|-------------|
| `id` | BIGINT UNSIGNED | Primary key, auto-increment |
| `order_id` | BIGINT UNSIGNED | Foreign key to `orders.id` |
| `voucher_code_id` | BIGINT UNSIGNED | Foreign key to `voucher_codes.id` |
| `discount_amount_applied` | DECIMAL(10,2) | Actual discount amount applied |

**Indexes:**
- Primary key: `id`
- Foreign key: `order_id` → `orders.id` (CASCADE)
- Foreign key: `voucher_code_id` → `voucher_codes.id` (CASCADE)

---

#### 12. `order_price_adjustments`
Detailed log of all price reductions applied to orders.

| Column | Type | Description |
|--------|------|-------------|
| `id` | BIGINT UNSIGNED | Primary key, auto-increment |
| `order_id` | BIGINT UNSIGNED | Foreign key to `orders.id` |
| `adjustment_type` | ENUM | 'voucher', 'bundle_discount', 'promo_rule', 'referral_credit' |
| `reference_code` | VARCHAR(100) | Identifier (e.g., "WELCOME20", "BUNDLE_GAMER_PACK") |
| `amount_deducted` | DECIMAL(10,2) | Amount deducted from order |
| `description` | VARCHAR(255) | Human-readable description for admin reports |
| `created_at` | DATETIME | Creation timestamp |

**Indexes:**
- Primary key: `id`
- Foreign key: `order_id` → `orders.id` (CASCADE)

---

### Content Management

#### 13. `stories`
Content/articles/stories with categories and publishing controls.

| Column | Type | Description |
|--------|------|-------------|
| `id` | INT UNSIGNED | Primary key, auto-increment |
| `title` | VARCHAR(255) | Story title |
| `slug` | VARCHAR(255) | URL-friendly slug (unique) |
| `category` | ENUM | 'story', 'promo', 'event', 'trailer', default: 'story' |
| `image` | VARCHAR(255) | Thumbnail image URL |
| `is_trailer` | BOOLEAN | Shows "TRAILER" badge, default: false |
| `excerpt` | VARCHAR(255) | Short summary for card display |
| `content` | LONGTEXT | Full article content |
| `status` | ENUM | 'draft' or 'published', default: 'draft' |
| `published_at` | DATETIME | Publication timestamp |
| `created_at` | DATETIME | Creation timestamp |
| `updated_at` | DATETIME | Last update timestamp |

**Indexes:**
- Primary key: `id`
- Unique: `slug`

---

#### 14. `promos`
Promotional campaigns with date ranges.

| Column | Type | Description |
|--------|------|-------------|
| `id` | INT UNSIGNED | Primary key, auto-increment |
| `name` | VARCHAR(150) | Promo name |
| `description` | TEXT | Promo description |
| `icon` | VARCHAR(255) | Icon or image URL |
| `start_date` | DATETIME | Start date (nullable) |
| `end_date` | DATETIME | End date (nullable) |
| `is_active` | BOOLEAN | Active status, default: true |
| `created_at` | DATETIME | Creation timestamp |
| `updated_at` | DATETIME | Last update timestamp |

**Indexes:**
- Primary key: `id`

---

#### 15. `how_it_works`
Step-by-step instructions for the "How It Works" section.

| Column | Type | Description |
|--------|------|-------------|
| `id` | INT UNSIGNED | Primary key, auto-increment |
| `title` | VARCHAR(100) | Step heading (e.g., "Select Product") |
| `description` | TEXT | Step explanation |
| `icon` | VARCHAR(255) | Icon path or class (e.g., 'fa-smile') |
| `sort_order` | INT | Display order (1, 2, 3, 4, 5), default: 0 |
| `is_active` | BOOLEAN | Active status, default: true |
| `created_at` | DATETIME | Creation timestamp |
| `updated_at` | DATETIME | Last update timestamp |

**Indexes:**
- Primary key: `id`

---

#### 16. `customer_support`
Customer support channels/contact methods.

| Column | Type | Description |
|--------|------|-------------|
| `id` | INT UNSIGNED | Primary key, auto-increment |
| `label` | VARCHAR(100) | Button label (e.g., "Email", "Viber") |
| `link` | VARCHAR(255) | Destination URL (e.g., "mailto:support@playpass.ph") |
| `icon` | VARCHAR(255) | Icon path or class |
| `sort_order` | INT | Display order, default: 0 |
| `is_active` | BOOLEAN | Active status, default: true |
| `created_at` | DATETIME | Creation timestamp |
| `updated_at` | DATETIME | Last update timestamp |

**Indexes:**
- Primary key: `id`

---

## Models

### Available Models

#### 1. `UserModel`
**Table:** `users`

**Features:**
- Auto-generates UUID on insert
- Auto-hashes passwords before insert/update
- Soft deletes enabled
- Validation rules for email uniqueness and role

**Allowed Fields:**
- `uuid`, `email`, `phone`, `password_hash`, `role`, `status`
- `email_verified_at`, `phone_verified_at`, `last_login_at`, `last_activity_at`

**Callbacks:**
- `beforeInsert`: `generateUUID`, `hashPassword`
- `beforeUpdate`: `hashPassword`

---

#### 2. `ProductModel`
**Table:** `products`

**Features:**
- Soft deletes disabled
- Joins with brands table
- Custom method: `getFilteredProducts()` - filters by brand, price range, duration

**Allowed Fields:**
- `sku`, `name`, `description`, `price`, `thumbnail_url`
- `is_featured`, `maya_product_code`, `points_to_earn`
- `is_bundle`, `is_active`

**Custom Methods:**
- `getFilteredProducts($filters, $limit, $offset)` - Returns filtered products with brand info

---

#### 3. `BrandModel`
**Table:** `brands`

**Features:**
- Simple CRUD model
- Timestamps enabled

**Allowed Fields:**
- `name`, `logo`, `is_enabled`

---

#### 4. `VoucherCodeModel`
**Table:** `voucher_codes`

**Features:**
- Timestamps disabled (handled manually)
- Custom validation logic

**Allowed Fields:**
- `campaign_id`, `code`, `is_redeemed`, `redeemed_at`, `redeemed_by_user_id`

**Custom Methods:**
- `getValidVoucherByCode($code)` - Validates voucher code against campaign rules (dates, redemption status)

---

#### 5. `OrderModel`
**Table:** `orders`

**Features:**
- Automatic validation prevents negative grand totals
- Timestamps enabled

**Allowed Fields:**
- `user_id`, `subtotal`, `discount_total`, `grand_total`
- `payment_status`, `maya_checkout_id`

**Validation Rules:**
- `grand_total`: required, decimal, >= 0
- `user_id`: required, integer

---

#### 6. `StoryModel`
**Table:** `stories`

**Features:**
- Timestamps enabled
- Multiple query methods for different use cases

**Allowed Fields:**
- `title`, `slug`, `category`, `image`, `is_trailer`
- `excerpt`, `content`, `status`, `published_at`

**Custom Methods:**
- `getStories($category, $limit, $offset)` - Infinite scroll with category filter
- `getLatestStories($limit)` - Latest published stories
- `getPublished($limit)` - Published stories only

---

#### 7. `PromosModel`
**Table:** `promos`

**Features:**
- Timestamps enabled
- Date-based active status checking

**Allowed Fields:**
- `name`, `description`, `logo`, `start_date`, `end_date`, `is_active`

**Custom Methods:**
- `getActivePromos()` - Returns active promos based on current date and `is_active` flag

---

#### 8. `HowItWorksModel`
**Table:** `how_it_works`

**Note:** Model file exists but content was not fully read. Based on migration, it should handle:
- Step ordering via `sort_order`
- Active/inactive status
- Icon and description management

---

#### 9. `CustomerSupportModel`
**Table:** `customer_support`

**Features:**
- Timestamps enabled
- Custom query method

**Allowed Fields:**
- `label`, `link`, `icon`, `sort_order`, `is_active`

**Custom Methods:**
- `getChannels()` - Returns active channels ordered by `sort_order`

---

### Missing Models

The following tables do not have corresponding models (may be accessed via Query Builder or need to be created):

1. **`point_ledger`** - Points transaction log
2. **`bundle_items`** - Bundle product relationships
3. **`voucher_campaigns`** - Voucher campaign rules
4. **`voucher_applicability`** - Voucher-to-product mapping
5. **`order_items`** - Order line items
6. **`order_applied_vouchers`** - Voucher usage tracking
7. **`order_price_adjustments`** - Price adjustment log

---

## Entity Relationships

### User Flow
```
users
  ├── point_ledger (one-to-many)
  ├── orders (one-to-many)
  └── self-referential (referred_by_user_id)
```

### Product Flow
```
brands
  └── products (one-to-many)
      ├── bundle_items (parent/child relationships)
      ├── order_items (one-to-many)
      └── voucher_applicability (many-to-many via junction table)
```

### Order Flow
```
orders
  ├── order_items (one-to-many)
  ├── order_applied_vouchers (one-to-many)
  └── order_price_adjustments (one-to-many)
```

### Voucher Flow
```
voucher_campaigns
  ├── voucher_codes (one-to-many)
  └── voucher_applicability (one-to-many)
      └── products (many-to-many)
```

---

## Key Business Logic

### Points System
- Points are tracked in `point_ledger` (source of truth)
- `users.current_points_balance` is a cached value for performance
- Transaction types: `purchase_reward`, `redemption`, `referral_bonus`, `adjustment`

### Referral System
- Each user has a unique `my_referral_code`
- Users can be referred by another user (`referred_by_user_id`)
- Referral bonuses are logged in `point_ledger` with type `referral_bonus`

### Voucher System
- Two code types: `universal` (one code for all) or `unique_batch` (individual codes)
- Discounts can be `fixed_amount` or `percentage`
- Vouchers can be stackable or non-stackable
- Date-based validation via `start_date` and `end_date`
- Product-specific applicability via `voucher_applicability` table

### Bundle Products
- Products with `is_bundle = true` are bundles
- Bundle contents defined in `bundle_items` table
- Supports quantity per item in bundle

### Order Pricing
- `subtotal`: Base total before discounts
- `discount_total`: Sum of all discounts
- `points_redeemed_value`: Points converted to currency
- `grand_total`: Final amount after all adjustments
- All adjustments logged in `order_price_adjustments` for audit trail

---

## Migration Order

Migrations should be run in this order (based on foreign key dependencies):

1. `CreateUsersAndPoints` - Creates `users` and `point_ledger`
2. `CreateBrands` - Creates `brands`
3. `CreateProductsTable` - Creates `products` (depends on `brands`)
4. `CreateBundleItems` - Creates `bundle_items` (depends on `products`)
5. `CreateVouchersTable` - Creates voucher system (depends on `products`)
6. `CreateOrdersTable` - Creates order system (depends on `users`, `voucher_codes`)
7. `CreateTransactionLogsTable` - Creates `order_price_adjustments` (depends on `orders`)
8. `CreateStories` - Content management
9. `AddCategoryToStories` - Adds category to `stories`
10. `CreatePromos` - Promotional campaigns
11. `CreateHowItWorks` - How it works steps
12. `CreateCustomerSupportTable` - Support channels

---

## Notes

- All tables use `created_at` and `updated_at` timestamps (except `point_ledger` and `voucher_codes` which handle dates manually)
- Foreign keys use CASCADE on delete/update where appropriate
- Soft deletes are enabled only on `users` table
- The system integrates with Maya payment gateway (`maya_checkout_id`, `maya_reference_number`, `maya_product_code`)
- Product prices are snapshotted in `order_items.price_at_purchase` to preserve historical accuracy


