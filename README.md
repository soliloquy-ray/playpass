# PlayPass E-Commerce Platform

<div align="center">

![PlayPass Logo](public/favicon.ico)

**A comprehensive CodeIgniter 4-based e-commerce platform for selling digital products with integrated payment processing, voucher system, and content management.**

[![PHP Version](https://img.shields.io/badge/PHP-8.1%2B-blue.svg)](https://www.php.net/)
[![CodeIgniter](https://img.shields.io/badge/CodeIgniter-4.0-orange.svg)](https://codeigniter.com/)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

</div>

---

## 📋 Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Installation & Setup](#installation--setup)
- [Project Structure](#project-structure)
- [Database Schema](#database-schema)
- [API Endpoints](#api-endpoints)
- [Admin Panel](#admin-panel)
- [User Features](#user-features)
- [Payment Integration](#payment-integration)
- [Voucher System](#voucher-system)
- [Content Management](#content-management)
- [Development Guidelines](#development-guidelines)
- [Deployment](#deployment)
- [Contributing](#contributing)
- [License](#license)

---

## 🎯 Overview

**PlayPass** is a full-featured e-commerce platform designed specifically for selling digital products such as gaming bundles, subscription codes, e-PINs, and other digital goods. Built with CodeIgniter 4, it provides a robust, scalable solution with integrated payment processing, advanced voucher/discount management, loyalty points system, referral program, and comprehensive content management capabilities.

### Purpose

PlayPass serves as a complete digital marketplace solution that enables businesses to:
- Sell digital products with instant delivery via SMS/Email
- Manage promotional campaigns and discount vouchers
- Process payments securely through Maya payment gateway
- Track customer loyalty through points and referral systems
- Publish and manage content (stories, articles, trailers, events)
- Provide comprehensive admin tools for inventory and order management

### Key Highlights

- **Instant Digital Delivery**: Automated product delivery via SMS/Email integration
- **Advanced Voucher System**: Flexible discount codes with stacking rules and campaign management
- **Loyalty Points**: Earn and redeem points system with transaction ledger
- **Referral Program**: Built-in referral tracking and bonus system
- **Content Management**: Full CMS for stories, articles, promos, and events
- **Responsive Design**: Mobile-first, dark-themed UI with modern UX
- **Admin Dashboard**: Comprehensive management interface for all operations

---

## ✨ Features

### Core E-Commerce Features

- **Product Catalog Management**
  - Product CRUD operations with rich metadata
  - Brand management and categorization
  - Bundle products support
  - Featured and new product flags
  - Product images and thumbnails
  - SKU management
  - Price and points configuration

- **Shopping & Checkout**
  - Product browsing with filters (brand, price, duration)
  - Infinite scroll product loading
  - Shopping cart functionality
  - Secure checkout process
  - Multiple payment methods support
  - Order tracking and history

- **Order Management**
  - Complete order lifecycle tracking
  - Payment status management (pending, paid, failed, refunded)
  - Fulfillment status tracking (pending, sent, failed)
  - Order history for users
  - Detailed order breakdowns
  - Price adjustment logging

### Payment & Financial

- **Maya Payment Gateway Integration**
  - Secure payment processing
  - Checkout initiation and management
  - Product disbursement API integration
  - Payment status webhooks
  - Idempotency handling for API calls
  - Transaction reference tracking

- **Voucher & Discount System**
  - Fixed amount and percentage discounts
  - Voucher code generation and management
  - Campaign-based voucher distribution
  - Stacking rules (stackable/non-stackable)
  - Minimum spend requirements
  - Usage limits and expiration dates
  - Product-specific voucher applicability

### Loyalty & Rewards

- **Points System**
  - Earn points on purchases
  - Redeem points for discounts
  - Transaction ledger for all point movements
  - Points balance caching
  - Purchase rewards, referral bonuses, and adjustments

- **Referral Program**
  - Unique referral code generation
  - Referral tracking and bonuses
  - Self-referential user relationships
  - Referral bonus points distribution

### Content Management

- **Stories & Articles**
  - Multi-category content (Stories, Promos, Events, Trailers)
  - Rich text content with images
  - Publishing workflow (draft/published)
  - Related stories algorithm
  - Category-based filtering
  - SEO-friendly slugs

- **Promotional Content**
  - Promo campaign management
  - Date-based activation
  - Promo icons and descriptions
  - Active/inactive status control

- **Carousel Management**
  - Homepage hero carousel
  - Slide ordering and management
  - Image and link configuration

- **How It Works Section**
  - Step-by-step instructions
  - Custom icons and descriptions
  - Sortable ordering

- **Customer Support Channels**
  - Support channel management
  - Links and icons configuration
  - Active/inactive status

### User Management

- **Authentication**
  - Email/password registration
  - Social login (Google, Facebook OAuth)
  - Session-based authentication
  - Password reset functionality
  - Role-based access control (customer/admin)

- **User Dashboard**
  - Order history
  - Points balance and transactions
  - Referral code management
  - Profile management

### Admin Features

- **Dashboard**
  - Overview statistics
  - Recent orders
  - System health monitoring

- **Product Management**
  - Full CRUD operations
  - Bulk operations
  - Image uploads
  - Brand assignment

- **Order Management**
  - Order status updates
  - Payment processing
  - Fulfillment tracking
  - Refund processing

- **Voucher Management**
  - Campaign creation
  - Code generation
  - Usage tracking
  - Analytics

- **Content Management**
  - Stories/articles editor
  - Promo management
  - Carousel configuration
  - Customer support channels

---

## 🛠 Tech Stack

### Backend

- **Framework**: CodeIgniter 4.0+
- **PHP**: 8.1 or higher
- **Database**: MySQL/MariaDB
- **Architecture**: MVC (Model-View-Controller)

### Frontend

- **HTML5/CSS3**: Custom dark-themed design system
- **JavaScript**: Vanilla JS (ES6+)
- **Responsive Design**: Mobile-first approach
- **UI Components**: Reusable Cell-based components

### Libraries & Dependencies

- **ramsey/uuid**: UUID generation for users and orders
- **fakerphp/faker**: Test data generation (dev)
- **phpunit/phpunit**: Testing framework

### External Integrations

- **Maya Payment Gateway**: Payment processing and product disbursement
- **OAuth Providers**: Google and Facebook authentication

### Development Tools

- **Composer**: Dependency management
- **PHPUnit**: Unit and integration testing
- **CodeIgniter Spark**: CLI tool for migrations, seeds, etc.

---

## 🚀 Installation & Setup

### Prerequisites

- PHP 8.1 or higher
- Composer
- MySQL 5.7+ or MariaDB 10.3+
- Web server (Apache/Nginx) or PHP built-in server
- Node.js (optional, for asset compilation)

### Step 1: Clone Repository

```bash
git clone https://github.com/soliloquy-ray/playpass.git
cd playpass
```

### Step 2: Install Dependencies

```bash
composer install
```

### Step 3: Environment Configuration

Copy the environment file and configure:

```bash
cp env .env
```

Edit `.env` and configure:

```ini
# Database
database.default.hostname = localhost
database.default.database = playpass_db
database.default.username = root
database.default.password = your_password
database.default.DBDriver = MySQLi

# App Configuration
app.baseURL = 'http://localhost:8080/'
app.forceGlobalSecureRequests = false

# Maya Payment Gateway
MAYA_BASE_URL = https://kyuubi-external-api-staging.voyagerapis.com
MAYA_CLIENT_ID = your_client_id
MAYA_SECRET_KEY = your_secret_key
MAYA_PUBLIC_KEY = your_public_key

# Encryption
encryption.key = your_32_character_encryption_key
```

### Step 4: Database Setup

Create the database:

```sql
CREATE DATABASE playpass_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Run migrations:

```bash
php spark migrate
```

Run seeders (optional, for sample data):

```bash
php spark db:seed BrandProductSeeder
php spark db:seed SettingsSeeder
```

### Step 5: Set Permissions

Ensure writable directories have proper permissions:

```bash
chmod -R 755 writable/
chmod -R 755 public/uploads/
```

### Step 6: Create Admin User

Access the admin creation script or use the admin panel:

```bash
php check_admin.php
```

Or create manually via database:

```sql
INSERT INTO admins (email, password_hash, role) 
VALUES ('admin@playpass.com', '$2y$10$...', 'super_admin');
```

### Step 7: Start Development Server

```bash
php spark serve
```

Access the application at `http://localhost:8080`

---

## 📁 Project Structure

```
playpass/
├── app/
│   ├── Cells/                 # Reusable UI components (Cell pattern)
│   │   ├── FeaturedProductsCell.php
│   │   ├── NewProductsCell.php
│   │   ├── PromosCell.php
│   │   ├── StoriesCell.php
│   │   └── StoryItemCell.php
│   ├── Config/                # Configuration files
│   │   ├── App.php
│   │   ├── Database.php
│   │   ├── Routes.php
│   │   └── Services.php
│   ├── Controllers/           # Request handlers
│   │   ├── Admin/            # Admin panel controllers
│   │   │   ├── AuthController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── ProductController.php
│   │   │   ├── StoryController.php
│   │   │   └── VoucherController.php
│   │   ├── Auth.php          # User authentication
│   │   ├── Checkout.php      # Checkout processing
│   │   ├── Home.php          # Homepage
│   │   ├── ProductsController.php
│   │   ├── StoriesController.php
│   │   └── User.php          # User dashboard
│   ├── Database/
│   │   ├── Migrations/       # Database migrations
│   │   └── Seeds/           # Database seeders
│   ├── Filters/             # Middleware/filters
│   │   └── AdminGuard.php   # Admin authentication filter
│   ├── Libraries/            # Reusable services
│   │   ├── MayaService.php  # Payment gateway integration
│   │   └── VoucherEngine.php # Voucher logic
│   ├── Models/              # Data models
│   │   ├── OrderModel.php
│   │   ├── ProductModel.php
│   │   ├── StoryModel.php
│   │   ├── UserModel.php
│   │   └── VoucherCodeModel.php
│   └── Views/               # View templates
│       ├── cells/           # Cell view templates
│       ├── layouts/         # Layout templates
│       ├── admin/          # Admin panel views
│       ├── auth/           # Authentication views
│       ├── home/           # Homepage views
│       ├── products/      # Product views
│       └── stories/       # Story views
├── public/
│   ├── assets/
│   │   ├── css/
│   │   │   ├── style.css   # Main stylesheet
│   │   │   └── admin.css   # Admin panel styles
│   │   └── js/
│   │       ├── carousel.js
│   │       ├── checkout.js
│   │       └── ui.js
│   ├── brands/             # Brand logos
│   ├── promo/              # Promo images
│   └── uploads/            # User uploads
├── tests/                  # Test suite
├── writable/               # Writable directories
│   ├── cache/
│   ├── logs/
│   └── uploads/
├── .env                    # Environment configuration
├── composer.json           # PHP dependencies
└── README.md              # This file
```

---

## 🗄 Database Schema

### Core Tables

#### Users & Authentication
- **users**: User accounts with authentication, referral codes, and points balance
- **admins**: Admin user accounts with role-based access
- **point_ledger**: Transaction log for all points movements

#### Products & Orders
- **brands**: Brand/company information
- **products**: Product catalog with pricing, images, and metadata
- **orders**: Order headers with payment and fulfillment status
- **order_items**: Order line items with snapshot pricing
- **order_applied_vouchers**: Voucher usage tracking
- **order_price_adjustments**: Detailed price adjustment logs

#### Vouchers & Discounts
- **voucher_campaigns**: Voucher campaign rules and settings
- **voucher_codes**: Individual voucher codes
- **voucher_applicability**: Product-to-voucher mapping

#### Content Management
- **stories**: Articles/stories with categories and publishing controls
- **promos**: Promotional campaigns with date ranges
- **carousel_slides**: Homepage carousel slides
- **how_it_works**: Step-by-step instructions
- **customer_support**: Support channel information

For detailed schema documentation, see [BLUEPRINT.md](BLUEPRINT.md).

---

## 🔌 API Endpoints

### Public Endpoints

#### Authentication
- `GET /app/login` - Login page
- `POST /app/login` - Process login
- `GET /app/register` - Registration page
- `POST /app/register` - Process registration
- `GET /app/logout` - Logout
- `GET /app/auth/google` - Google OAuth redirect
- `GET /app/auth/google/callback` - Google OAuth callback
- `GET /app/auth/facebook` - Facebook OAuth redirect
- `GET /app/auth/facebook/callback` - Facebook OAuth callback

#### Products
- `GET /app/buy-now` - Product listing page
- `GET /app/products/fetch` - AJAX product loading (with filters)
- `GET /app/buy/{id}` - Product detail page

#### Stories
- `GET /app/stories` - Stories listing page
- `GET /app/stories/fetch` - AJAX stories loading (with category filter)
- `GET /app/stories/{slug}` - Individual story page

#### Checkout
- `POST /app/checkout/process` - Process checkout and payment

#### User Dashboard
- `GET /app/account` - User dashboard (requires authentication)

### Admin Endpoints

All admin endpoints require authentication and are protected by `AdminGuard` filter.

#### Dashboard
- `GET /admin/dashboard` - Admin dashboard

#### Products
- `GET /admin/products` - Product list
- `GET /admin/products/new` - Create product form
- `POST /admin/products/create` - Create product
- `GET /admin/products/edit/{id}` - Edit product form
- `POST /admin/products/update/{id}` - Update product
- `POST /admin/products/delete/{id}` - Delete product

#### Stories
- `GET /admin/stories` - Story list
- `GET /admin/stories/new` - Create story form
- `POST /admin/stories/create` - Create story
- `GET /admin/stories/edit/{id}` - Edit story form
- `POST /admin/stories/update/{id}` - Update story
- `POST /admin/stories/delete/{id}` - Delete story

#### Vouchers
- `GET /admin/vouchers` - Voucher campaign list
- `GET /admin/vouchers/new` - Create voucher campaign
- `POST /admin/vouchers/create` - Create voucher campaign
- `GET /admin/vouchers/codes/{id}` - View voucher codes for campaign
- `POST /admin/vouchers/generate-codes/{id}` - Generate voucher codes

#### Other Admin Endpoints
- Brands, Promos, Carousel, How It Works, Customer Support management
- Similar CRUD patterns for all content types

---

## 👨‍💼 Admin Panel

The admin panel provides comprehensive management tools for all aspects of the platform.

### Access

Navigate to `/admin/login` and authenticate with admin credentials.

### Features

- **Dashboard**: Overview statistics, recent orders, system health
- **Product Management**: Full CRUD, image uploads, brand assignment
- **Order Management**: View orders, update status, process refunds
- **Voucher Management**: Create campaigns, generate codes, track usage
- **Content Management**: Stories, promos, carousel, support channels
- **User Management**: View users, manage accounts
- **Settings**: System configuration

### Admin Roles

- **admin**: Standard admin access
- **super_admin**: Full system access with additional privileges

---

## 👤 User Features

### Registration & Authentication

- Email/password registration
- Social login (Google, Facebook)
- Password reset functionality
- Email verification (optional)

### Shopping Experience

- Browse products with filters
- Search functionality
- Product detail pages
- Shopping cart
- Secure checkout
- Order tracking

### Account Management

- View order history
- Track points balance
- Manage referral code
- Update profile information
- View transaction history

### Content Browsing

- Browse stories/articles
- Filter by category (Promos, Events, Stories, Trailers)
- Read full articles
- View related content

---

## 💳 Payment Integration

### Maya Payment Gateway

PlayPass integrates with Maya payment gateway for secure payment processing and digital product disbursement.

#### Configuration

Set the following in `.env`:

```ini
MAYA_BASE_URL=https://kyuubi-external-api-staging.voyagerapis.com
MAYA_CLIENT_ID=your_client_id
MAYA_SECRET_KEY=your_secret_key
MAYA_PUBLIC_KEY=your_public_key
```

#### Features

- **Checkout Initiation**: Create payment checkout sessions
- **Product Disbursement**: Automated delivery of digital products
- **Payment Status Tracking**: Webhook support for payment updates
- **Idempotency**: UUID-based request tracking to prevent duplicates
- **Transaction Logging**: Complete audit trail of all transactions

#### Flow

1. User initiates checkout
2. System creates order with `pending` status
3. Maya checkout session is created
4. User completes payment on Maya
5. Webhook updates order status
6. Product is disbursed via Maya API
7. Order status updated to `paid` and `sent`

---

## 🎫 Voucher System

### Features

- **Campaign Management**: Create voucher campaigns with rules
- **Code Generation**: Bulk or individual code generation
- **Discount Types**: Fixed amount or percentage discounts
- **Stacking Rules**: Control whether vouchers can be combined
- **Minimum Spend**: Set minimum purchase requirements
- **Product Applicability**: Restrict vouchers to specific products
- **Usage Limits**: Per-user and total usage limits
- **Expiration Dates**: Time-based voucher validity

### Voucher Engine

The `VoucherEngine` library handles all voucher logic:

- Validation of voucher codes
- Discount calculation
- Stacking rule enforcement
- Minimum spend checks
- Usage limit verification

### Usage

```php
$voucherEngine = new VoucherEngine();
$result = $voucherEngine->applyVoucher($code, $subtotal);

if ($result['success']) {
    $discount = $result['discount_amount'];
    $newTotal = $result['new_total'];
}
```

---

## 📝 Content Management

### Stories System

- **Categories**: Stories, Promos, Events, Trailers
- **Publishing Workflow**: Draft and published states
- **Rich Content**: Full text content with images
- **Related Stories**: Category-based related content algorithm
- **SEO-Friendly**: Slug-based URLs
- **Filtering**: Category-based filtering on listing page

### Promo Management

- Date-based activation
- Icon and description management
- Active/inactive status control

### Carousel Management

- Homepage hero carousel
- Slide ordering
- Image and link configuration

### How It Works

- Step-by-step instructions
- Custom icons
- Sortable ordering

---

## 💻 Development Guidelines

### Code Style

- Follow PSR-12 coding standards
- Use meaningful variable and function names
- Add comments for complex logic
- Keep functions focused and single-purpose

### Model Patterns

**Validation in Models**: All validation rules should be in model `$validationRules`:

```php
protected $validationRules = [
    'email' => 'required|valid_email|is_unique[users.email,id,{id}]',
    'price' => 'required|decimal|greater_than[0]',
];
```

**Callbacks**: Use model callbacks for transformations:

```php
protected $beforeInsert = ['generateUUID', 'hashPassword'];
protected $beforeUpdate = ['hashPassword'];
```

### Controller Patterns

- Keep controllers thin; move business logic to models or libraries
- Return JSON for API endpoints
- Use redirects with flash messages for form submissions
- Validate input before processing

### View Patterns

- Use Cell components for reusable UI elements
- Keep views clean; minimal PHP logic
- Use layout templates for consistency
- Escape all output with `esc()`

### Testing

Run tests:

```bash
composer test
```

Write tests for:
- Model validation
- Business logic in libraries
- Critical user flows

### Database Migrations

Create migration:

```bash
php spark migrate:create MigrationName
```

Run migrations:

```bash
php spark migrate
```

Rollback:

```bash
php spark migrate:rollback
```

---

## 🚢 Deployment

### Production Checklist

- [ ] Update `.env` with production values
- [ ] Set `app.production = true` in `App.php`
- [ ] Configure secure database credentials
- [ ] Set up SSL certificate
- [ ] Configure proper file permissions
- [ ] Set up error logging
- [ ] Configure backup strategy
- [ ] Set up monitoring
- [ ] Test payment gateway in production mode
- [ ] Configure email settings
- [ ] Set up CDN for static assets (optional)

### Server Requirements

- PHP 8.1+
- MySQL 5.7+ or MariaDB 10.3+
- Apache 2.4+ or Nginx 1.18+
- mod_rewrite enabled (Apache)
- OpenSSL extension
- PDO MySQL extension
- GD or Imagick extension (for image processing)

### Environment Variables

Ensure all sensitive data is in `.env`:
- Database credentials
- Encryption key
- Maya API credentials
- OAuth client IDs and secrets

### Security Considerations

- Use HTTPS in production
- Set secure session configuration
- Enable CSRF protection
- Sanitize all user input
- Use prepared statements (CodeIgniter does this automatically)
- Regular security updates
- Strong password requirements
- Rate limiting on authentication endpoints

---

## 🤝 Contributing

Contributions are welcome! Please follow these guidelines:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Make your changes
4. Write or update tests
5. Ensure all tests pass
6. Commit your changes (`git commit -m 'Add amazing feature'`)
7. Push to the branch (`git push origin feature/amazing-feature`)
8. Open a Pull Request

### Contribution Guidelines

- Follow PSR-12 coding standards
- Write meaningful commit messages
- Add tests for new features
- Update documentation as needed
- Keep pull requests focused and small

---

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 📞 Support

For support, email support@playpass.com or open an issue in the repository.

---

## 🙏 Acknowledgments

- CodeIgniter 4 framework
- Maya Payment Gateway
- All contributors and users

---

<div align="center">

**Built with ❤️ using CodeIgniter 4**

[Documentation](BLUEPRINT.md) • [Components Guide](COMPONENTS_GUIDE.md) • [UI Implementation](UI_IMPLEMENTATION.md)

</div>
