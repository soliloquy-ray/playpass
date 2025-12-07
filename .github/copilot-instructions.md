# PlayPass Copilot Instructions

This is a **CodeIgniter 4 e-commerce platform** for selling digital products (e.g., gaming bundles, subscriptions) with **Maya payment gateway integration** and **voucher/discount system**.

## Architecture Overview

### Core Data Model
- **Users**: Authentication with UUID, soft deletes, role-based access (customer/admin/super_admin)
- **Products**: Sellable items with `maya_product_code` linking to external disbursement API
- **Orders**: Aggregate purchase data (subtotal, discount, total) with `payment_status` and `maya_checkout_id` for idempotency
- **Vouchers**: Discount codes with stacking rules, min spend thresholds, and two discount types (`fixed_amount` or `percentage`)

**Key pattern**: All models use CodeIgniter 4's built-in validation rules and callbacks (e.g., `beforeInsert`, `beforeUpdate`) in model properties, not in controllers.

### Service Layer Architecture

**MayaService** (`app/Libraries/MayaService.php`):
- Wrapper around Maya payment gateway API (credit card processing)
- Methods: `initiateCheckout()`, `disburseProduct()`, `processRefund()`
- Uses CURL for HTTP requests via CodeIgniter's `Services::curlrequest()`
- **Critical**: Stores `maya_checkout_id` in orders table to track API requests for idempotency

**VoucherEngine** (`app/Libraries/VoucherEngine.php`):
- Business logic for discount calculation and validation
- Handles stacking rules: non-stackable vouchers block other vouchers
- Returns structured response: `['success', 'message', 'voucher_data', 'discount_amount', 'new_total']`
- **Convention**: Always returns data structure that validates against rules (e.g., negative totals prevented by model validation)

### Request Flow: Checkout Process
1. Frontend sends JSON to `POST /checkout/process` with `user_id`, `product_id`, `recipient`, optional `voucher_code`
2. **CheckoutController**:
   - Fetches product from ProductModel
   - Calls `VoucherEngine->applyVoucher()` for discount logic
   - Creates order with status `pending` **before** calling external API (idempotency)
   - Passes order to `MayaService->disburseProduct()` for payment
   - Returns `maya_checkout_id` to frontend for webhook tracking

## Critical Conventions & Patterns

### Model Validation
- **All** data validation rules live in model `protected $validationRules` properties
- Controllers validate via `$model->validate($data)` before insert/update
- Use `validationMessages` to provide user-friendly error text (e.g., OrderModel prevents negative totals)
- Example: `UserModel` has regex for password strength and auto-hashes via `beforeInsert` callback

### Authentication & Authorization
- **Session-based**: Check `session()->get('isLoggedIn')` and `session()->get('role')`
- **AdminGuard filter**: Applied to routes in `app/Config/Routes.php` using `['filter' => 'AdminGuard']`
- Failed auth logs to logger: `log_message('warning', 'Unauthorized admin access...')`
- No JWT or token-based auth currently

### Error Handling
- Controllers return JSON responses: `['status' => 'error', 'message' => '...']`
- Model validation failures redirect back with `->withInput()->with('errors', $validator->getErrors())`
- VoucherEngine returns structured response with `success` boolean—caller checks this before proceeding

### UUID & Idempotency
- All users get UUID via model callback (`generateUUID` in UserModel)
- Orders use UUID-based `maya_checkout_id` to prevent duplicate payments on API retry
- Pattern: Generate ID locally before external API call

## Developer Workflows

### Running Tests
```bash
composer test
# Runs PHPUnit with phpunit.xml.dist config
# Tests in tests/unit/ and tests/database/
```

### Database Setup
- Migrations in `app/Database/Migrations/` (auto-loaded by CodeIgniter)
- Create new migration: `php spark migrate:create <name>`
- Test database configured in `app/Config/Database.php` or `.env` under `[tests]` group
- All migrations use timestamp prefix (e.g., `2025-11-30-184000_CreateUsersAndPoints.php`)

### Adding Routes
- Edit `app/Config/Routes.php`
- Use resource routing for CRUD: `$routes->resource('products')`
- Group protected routes: `$routes->group('admin', ['filter' => 'AdminGuard'], function($routes) { ... })`

### Common Model Patterns
```php
// Validation in model (not controller)
protected $validationRules = [
    'email' => 'required|valid_email|is_unique[users.email,id,{id}]',
];

// Auto-transformation via callbacks
protected $beforeInsert = ['generateUUID', 'hashPassword'];
protected $beforeUpdate = ['hashPassword'];

protected function generateUUID(array $data) {
    $data['data']['uuid'] = Uuid::uuid4()->toString();
    return $data;
}
```

## Integration Points

### External APIs
- **Maya Payment Gateway**: PHP-based CURL integration in `MayaService`
  - Credentials in `.env`: `MAYA_BASE_URL`, `MAYA_CLIENT_ID`, `MAYA_SECRET_KEY`, `MAYA_PUBLIC_KEY`
  - Endpoints: `/checkout/v1/checkouts`, `/disbursement` (varies by environment)
- **Product Disbursement**: Depends on product type; Maya API handles delivery for game codes/subscriptions

### Key Dependencies
- **ramsey/uuid**: UUID generation (used in UserModel and order tracking)
- **phpunit/phpunit**: Testing framework
- **fakerphp/faker**: Test data generation

### Database Relationships
```
users (1) -----> (M) orders
users (1) -----> (M) voucher_applications
products (1) -----> (M) order_items
vouchers (1) -----> (M) voucher_applications
orders (1) -----> (M) transaction_logs
```

## Code Quality & Security Notes

- **SQL Injection Prevention**: Use CodeIgniter's query builder (e.g., `$model->where('id', $id)->first()`)
- **Password Security**: Hashed with `PASSWORD_DEFAULT` in UserModel::hashPassword()
- **Input Validation**: Always validate in models before accepting user input
- **Soft Deletes**: UserModel uses soft deletes (`useSoftDeletes = true`)
- **CURL Security**: MayaService uses POST with HTTPS and API credentials in environment variables

## File Organization

```
app/
  Config/          # Application config (Routes, Database, Services)
  Controllers/     # HTTP request handlers (Auth, Checkout, User, etc.)
  Models/          # Data access + business logic + validation
  Libraries/       # Reusable service logic (MayaService, VoucherEngine)
  Filters/         # Middleware (AdminGuard for authorization)
  Views/           # HTML templates (blade-like syntax)
  Database/        # Migrations and Seeds
public/
  index.php        # Entry point
tests/             # Unit & database tests
```

## Common Pitfalls to Avoid

1. **Putting validation in controllers**: Move to model `validationRules` property
2. **Ignoring idempotency**: Always store external API reference IDs (`maya_checkout_id`) before calling API
3. **Stacking vouchers without checking**: VoucherEngine handles logic—respect its return structure
4. **Hardcoding API credentials**: Use `.env` file and `getenv()` in services
5. **Not checking session role in AdminGuard**: Filter ensures admin-only access; don't duplicate checks
