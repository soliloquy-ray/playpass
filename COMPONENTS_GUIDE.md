# PlayPass UI Components - Comprehensive Guide

## Overview

The PlayPass UI has been completely rebuilt with a modular, reusable component-based architecture. All components follow the CodeIgniter 4 Cell pattern for maximum reusability and maintainability.

---

## 🎨 Available UI Component Cells

### 1. **ProductCell** - Product Card Display
**Location:** `app/Cells/ProductCell.php`
**View:** `app/Views/cells/product_card.php`

Renders a product card with image, name, price, points, and buy button.

**Usage:**
```php
<?= view_cell('App\Cells\ProductCell::renderCard', ['product' => $product]) ?>
```

**Features:**
- Product image with hover zoom effect
- Bundle and sale badges
- Price display in green
- Loyalty points display
- Responsive grid-friendly sizing

---

### 2. **ArticleCell** - Article/Story Card
**Location:** `app/Cells/ArticleCell.php`
**View:** `app/Views/cells/article_card.php`

Displays article metadata and excerpt with read more link.

**Usage:**
```php
<?= view_cell('App\Cells\ArticleCell::renderCard', ['article' => $article]) ?>
```

**Features:**
- Publication date
- Article title with link
- Excerpt text (auto-truncated)
- Read story link
- Hover effects

---

### 3. **StatCardCell** - Statistics Display
**Location:** `app/Cells/StatCardCell.php`
**View:** `app/Views/cells/stat_card.php`

Shows a metric/statistic with icon and label.

**Usage:**
```php
<?= view_cell('App\Cells\StatCardCell::renderCard', [
    'icon' => '📊',
    'number' => 1234,
    'label' => 'Total Orders',
    'unit' => '' // optional
]) ?>
```

**Features:**
- Large icon display
- Number with formatting
- Descriptive label
- Optional unit (e.g., ₱, %)
- Gradient background

---

### 4. **CategoryBadgeCell** - Category Selector
**Location:** `app/Cells/CategoryBadgeCell.php`
**View:** `app/Views/cells/category_badge.php`

Clickable category badge with icon and product count.

**Usage:**
```php
<?= view_cell('App\Cells\CategoryBadgeCell::renderBadge', [
    'category' => 'Games',
    'icon' => '🎮',
    'count' => 245,
    'url' => '/products?category=games'
]) ?>
```

**Features:**
- Large emoji icon
- Category name
- Product count badge
- Hover lift effect
- Clickable link

---

### 5. **HeroBannerCell** - Hero Section Banner
**Location:** `app/Cells/HeroBannerCell.php`
**View:** `app/Views/cells/hero_banner.php`

Promotional banner with title, subtitle, and CTA button.

**Usage:**
```php
<?= view_cell('App\Cells\HeroBannerCell::renderBanner', [
    'title' => 'Exclusive Deals',
    'subtitle' => 'Limited time offer',
    'button_text' => 'Shop Now',
    'button_url' => '/products',
    'button_class' => 'btn-primary'
]) ?>
```

**Features:**
- Customizable gradient background
- Large headline
- Subtitle text
- CTA button
- Full-width responsive design

---

### 6. **CtaButtonCell** - Call-to-Action Banner
**Location:** `app/Cells/CtaButtonCell.php`
**View:** `app/Views/cells/cta_banner.php`

Prominent CTA banner for conversions (e.g., signup, newsletter).

**Usage:**
```php
<?= view_cell('App\Cells\CtaButtonCell::renderBanner', [
    'title' => 'Ready to Get Started?',
    'subtitle' => 'Join millions of users',
    'button_text' => 'Sign Up Now',
    'button_url' => '/register',
    'icon' => '🚀'
]) ?>
```

**Features:**
- Icon emoji
- Large title and subtitle
- White button on colored background
- Pink gradient background
- Full-width design

---

### 7. **TestimonialCardCell** - Customer Reviews
**Location:** `app/Cells/TestimonialCardCell.php`
**View:** `app/Views/cells/testimonial_card.php`

Customer testimonial with rating and author info.

**Usage:**
```php
<?= view_cell('App\Cells\TestimonialCardCell::renderCard', [
    'name' => 'John Doe',
    'role' => 'Verified Buyer',
    'content' => 'Great service!',
    'rating' => 5,
    'avatar' => '/images/avatars/john.jpg'
]) ?>
```

**Features:**
- Star rating (1-5 stars)
- Quoted testimonial text
- Author name and role
- Avatar image (or initial circle)
- Card styling with hover effects

---

### 8. **ProductShowcaseCell** - Product Showcase Section
**Location:** `app/Cells/ProductShowcaseCell.php`
**View:** `app/Views/cells/product_showcase.php`

Section displaying multiple products with title and view all link.

**Usage:**
```php
<?= view_cell('App\Cells\ProductShowcaseCell::renderShowcase', [
    'title' => 'Trending Products',
    'products' => $products,
    'view_all_url' => '/products'
]) ?>
```

**Features:**
- Section title
- Auto-grid layout
- View all link
- Product cell integration

---

### 9. **FeaturedBannerCell** - Feature Promotion
**Location:** `app/Cells/FeaturedBannerCell.php`
**View:** `app/Views/cells/featured_banner.php`

Large featured product/promotion banner.

**Usage:**
```php
<?= view_cell('App\Cells\FeaturedBannerCell::renderBanner', [
    'title' => 'Premium Bundle',
    'description' => 'Get all games + 3 months streaming',
    'image_url' => '/images/featured.jpg',
    'button_text' => 'View Now',
    'button_url' => '/product/premium-bundle',
    'badge' => 'FEATURED'
]) ?>
```

**Features:**
- Large banner layout
- Gradient background with pattern
- Badge indicator
- Optional product image
- CTA button

---

## 📱 CSS Component Classes

### Grid System
```html
<!-- Auto-responsive grid (best for products) -->
<div class="grid grid-auto"></div>

<!-- Fixed column grids -->
<div class="grid grid-2"></div>  <!-- 2 columns -->
<div class="grid grid-3"></div>  <!-- 3 columns -->
<div class="grid grid-4"></div>  <!-- 4 columns -->
```

### Buttons
```html
<a href="#" class="btn btn-primary">Primary Button</a>
<button class="btn btn-secondary">Secondary Button</button>
<button class="btn btn-outline">Outline Button</button>
<button class="btn btn-small">Small Button</button>
<button class="btn btn-large">Large Button</button>
```

### Badges
```html
<span class="badge">Default</span>
<span class="badge badge-success">Success</span>
<span class="badge badge-warning">Warning</span>
```

### Alerts
```html
<div class="alert alert-success">Success message</div>
<div class="alert alert-error">Error message</div>
<div class="alert alert-warning">Warning message</div>
<div class="alert alert-info">Info message</div>
```

### Cards
```html
<div class="card">
    <h3>Card Title</h3>
    <p>Card content</p>
</div>
```

---

## 🎯 Page Layouts Using Components

### Homepage (`views/home/index.php`)
```php
<!-- Hero Carousel -->
<div class="hero-carousel">...</div>

<!-- Category Showcase -->
<?= view_cell('App\Cells\CategoryBadgeCell::renderBadge', [...]) ?>

<!-- Product Sections -->
<?= view_cell('App\Cells\ProductShowcaseCell::renderShowcase', [...]) ?>

<!-- Featured Banner -->
<?= view_cell('App\Cells\FeaturedBannerCell::renderBanner', [...]) ?>

<!-- CTA Banner -->
<?= view_cell('App\Cells\CtaButtonCell::renderBanner', [...]) ?>

<!-- Articles -->
<?= view_cell('App\Cells\ArticleCell::renderCard', [...]) ?>
```

### User Dashboard (`views/user/index.php`)
```php
<!-- Stat Cards -->
<div class="grid grid-3">
    <?= view_cell('App\Cells\StatCardCell::renderCard', ['icon' => '📦', 'number' => 12, 'label' => 'Orders']) ?>
    <?= view_cell('App\Cells\StatCardCell::renderCard', ['icon' => '⭐', 'number' => 3, 'label' => 'Subscriptions']) ?>
    <?= view_cell('App\Cells\StatCardCell::renderCard', ['icon' => '💰', 'number' => 1250, 'label' => 'Points']) ?>
</div>

<!-- Recent Orders Table -->
<table>
    <!-- order data -->
</table>
```

### Admin Dashboard (`views/admin/dashboard.php`)
```php
<!-- Stat Cards -->
<div class="grid grid-4">
    <?= view_cell('App\Cells\StatCardCell::renderCard', [...]) ?>
    <?= view_cell('App\Cells\StatCardCell::renderCard', [...]) ?>
    <?= view_cell('App\Cells\StatCardCell::renderCard', [...]) ?>
    <?= view_cell('App\Cells\StatCardCell::renderCard', [...]) ?>
</div>

<!-- Recent Orders -->
<table>
    <!-- order data -->
</table>
```

---

## 🎨 Color System

All components use CSS variables for theming:

```css
--bg-color: #0c0c0c          /* Main background */
--card-bg: #1a1a1a           /* Card background */
--primary: #d8369f           /* Pink - primary action */
--secondary: #051429         /* Dark blue */
--success: #4caf50           /* Green */
--warning: #ff9800           /* Orange */
--danger: #f44336            /* Red */
--text-main: #ffffff         /* Main text */
--text-muted: #a0a0a0        /* Muted text */
--text-light: #e0e0e0        /* Light text */
--border-color: #333         /* Borders */
```

---

## 📐 Responsive Breakpoints

- **Mobile:** ≤ 480px
- **Tablet:** 481px - 768px
- **Desktop:** ≥ 769px

All components automatically adapt to these breakpoints.

---

## 🔧 JavaScript Features

### Carousel (`assets/js/carousel.js`)
- Auto-play with 5-second interval
- Manual dot/arrow navigation
- Pause on hover

### UI Utilities (`assets/js/ui.js`)
- Mobile menu toggle
- Modal management
- Currency formatting
- Alert system
- Smooth scroll

### Checkout (`assets/js/checkout.js`)
- Voucher application
- Price calculations
- Payment processing

---

## 📋 Mobile Menu

The mobile menu component (`components/mobile_menu.php`) includes:
- Hamburger menu toggle
- Main navigation links
- User account links (if logged in)
- Login/Register buttons (if not logged in)
- Automatic close on link click
- Backdrop overlay

---

## 🚀 Best Practices

1. **Always use cells for reusable components**
   - Keeps views clean
   - Easy to maintain
   - Encapsulates logic

2. **Use CSS classes for styling**
   - `.card` - for card containers
   - `.btn` - for buttons
   - `.grid` - for layouts
   - `.badge` - for badges

3. **Responsive design first**
   - Use grid system
   - Components auto-adapt
   - Test on mobile

4. **Accessibility**
   - Use semantic HTML
   - Include aria labels
   - Proper color contrast
   - Keyboard navigation

---

## 📊 Component Organization

```
app/
├── Cells/
│   ├── ProductCell.php
│   ├── ArticleCell.php
│   ├── StatCardCell.php
│   ├── CategoryBadgeCell.php
│   ├── HeroBannerCell.php
│   ├── CtaButtonCell.php
│   ├── TestimonialCardCell.php
│   ├── ProductShowcaseCell.php
│   └── FeaturedBannerCell.php
│
└── Views/
    ├── cells/
    │   ├── product_card.php
    │   ├── article_card.php
    │   ├── stat_card.php
    │   ├── category_badge.php
    │   ├── hero_banner.php
    │   ├── cta_banner.php
    │   ├── testimonial_card.php
    │   ├── product_showcase.php
    │   └── featured_banner.php
    │
    ├── components/
    │   └── mobile_menu.php
    │
    └── layouts/
        └── main.php

public/assets/
├── css/
│   └── style.css (630+ lines)
└── js/
    ├── carousel.js
    ├── ui.js
    └── checkout.js
```

---

## 🎯 Next Steps

To extend the UI:

1. **Create new Cell class** in `app/Cells/`
2. **Create view file** in `app/Views/cells/`
3. **Add to Cell** `renderMethod()` that returns view
4. **Use anywhere** with `<?= view_cell(...) ?>`

Example:
```php
// app/Cells/MyComponentCell.php
public function render(array $data = []): string {
    return view('App\Cells\my_component', $data);
}
```

---

## 📖 Component Examples

### Show 3-column stat grid on dashboard:
```php
<div class="grid grid-3">
    <?= view_cell('App\Cells\StatCardCell::renderCard', ['icon' => '📊', 'number' => 123, 'label' => 'Total']) ?>
    <?= view_cell('App\Cells\StatCardCell::renderCard', ['icon' => '✓', 'number' => 456, 'label' => 'Completed']) ?>
    <?= view_cell('App\Cells\StatCardCell::renderCard', ['icon' => '⏳', 'number' => 789, 'label' => 'Pending']) ?>
</div>
```

### Show products in responsive grid:
```php
<div class="grid grid-auto">
    <?php foreach ($products as $product): ?>
        <?= view_cell('App\Cells\ProductCell::renderCard', ['product' => $product]) ?>
    <?php endforeach; ?>
</div>
```

### Show featured promotion:
```php
<?= view_cell('App\Cells\FeaturedBannerCell::renderBanner', [
    'title' => 'Special Offer',
    'description' => 'Limited time promotion',
    'image_url' => '/images/promo.jpg',
    'button_text' => 'Learn More',
    'button_url' => '/promo',
    'badge' => 'HOT DEAL'
]) ?>
```

---

**Last Updated:** December 7, 2025
**Version:** 1.0.0
**Framework:** CodeIgniter 4
