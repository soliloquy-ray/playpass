# PlayPass UI Quick Reference Guide

## 🎯 Common Patterns

### Creating a Product Grid
```php
<div class="grid grid-auto">
    <?php foreach ($products as $product): ?>
        <?= view_cell('App\Cells\ProductCell::renderCard', ['product' => $product]) ?>
    <?php endforeach; ?>
</div>
```

### Creating a 3-Column Layout
```php
<div class="grid grid-3">
    <div class="card">Column 1</div>
    <div class="card">Column 2</div>
    <div class="card">Column 3</div>
</div>
```

### Using Buttons
```html
<!-- Primary button -->
<a href="/checkout" class="btn btn-primary">Buy Now</a>

<!-- Secondary button -->
<button class="btn btn-secondary">Apply</button>

<!-- Large button (full width) -->
<button class="btn btn-primary btn-large">Submit</button>

<!-- Small button -->
<button class="btn btn-secondary btn-small">Edit</button>

<!-- Outline button -->
<button class="btn btn-outline">Cancel</button>
```

### Form Layout
```php
<form action="/submit" method="post">
    <?= csrf_field() ?>
    
    <div class="form-group">
        <label for="name">Full Name</label>
        <input type="text" id="name" name="name" class="input-dark" required>
    </div>

    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" class="input-dark" required>
    </div>

    <button type="submit" class="btn btn-primary btn-large">Submit</button>
</form>
```

### Responsive Grid Layout
```php
<!-- Desktop: 4 cols, Tablet: 2 cols, Mobile: 1 col -->
<div class="grid grid-4">
    <?php foreach ($items as $item): ?>
        <div class="card"><?= esc($item['name']) ?></div>
    <?php endforeach; ?>
</div>
```

### Displaying Alerts
```javascript
// Success message
showAlert('success', 'Order completed successfully!');

// Error message
showAlert('error', 'Something went wrong. Please try again.');

// Warning message
showAlert('warning', 'This action cannot be undone.');

// Info message
showAlert('info', 'Your order is being processed.');
```

### Using Badges
```html
<!-- Primary badge -->
<span class="badge">NEW</span>

<!-- Success badge -->
<span class="badge badge-success">IN STOCK</span>

<!-- Warning badge -->
<span class="badge badge-warning">SALE</span>
```

### Table Structure
```html
<div class="card">
    <table>
        <thead>
            <tr>
                <th style="text-align: left; padding: 12px 0;">Column 1</th>
                <th style="text-align: left; padding: 12px 0;">Column 2</th>
            </tr>
        </thead>
        <tbody>
            <tr style="border-bottom: 1px solid var(--border-color);">
                <td style="padding: 12px 0;">Data 1</td>
                <td style="padding: 12px 0;">Data 2</td>
            </tr>
        </tbody>
    </table>
</div>
```

### Alert Components
```html
<!-- Success alert -->
<div class="alert alert-success">
    ✓ Your changes have been saved successfully!
</div>

<!-- Error alert -->
<div class="alert alert-error">
    ✗ An error occurred. Please try again.
</div>

<!-- Warning alert -->
<div class="alert alert-warning">
    ⚠ This action is permanent and cannot be undone.
</div>

<!-- Info alert -->
<div class="alert alert-info">
    ℹ This is an informational message.
</div>
```

### Color Classes
```html
<p class="text-primary">Primary text (pink)</p>
<p class="text-success">Success text (green)</p>
<p class="text-warning">Warning text (orange)</p>
<p class="text-danger">Danger text (red)</p>
<p class="text-muted">Muted text (gray)</p>
```

### Utility Classes
```html
<!-- Margins -->
<div class="mt-10">10px top margin</div>
<div class="mt-20">20px top margin</div>
<div class="mb-30">30px bottom margin</div>

<!-- Text -->
<p class="text-center">Centered text</p>
<p class="text-right">Right-aligned text</p>
<p class="text-small">Small text</p>
<p class="text-large">Large text</p>

<!-- Visibility -->
<div class="hidden">Hidden element</div>
<div class="visible">Visible element</div>

<!-- Truncate -->
<p class="text-truncate">This text will be truncated with ellipsis...</p>
```

### Modal Usage
```javascript
// Open modal
Modal.open('myModalId');

// Close modal
Modal.close('myModalId');
```

```html
<!-- Modal HTML -->
<div class="modal" id="myModalId">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Modal Title</h2>
            <button class="modal-close">✕</button>
        </div>
        <p>Modal content goes here</p>
    </div>
</div>
```

## 🎨 CSS Variables

```css
:root {
    --bg-color: #0c0c0c;           /* Main background */
    --card-bg: #1a1a1a;             /* Card background */
    --primary: #d8369f;             /* Primary pink */
    --secondary: #051429;           /* Dark blue */
    --success: #4caf50;             /* Green */
    --warning: #ff9800;             /* Orange */
    --danger: #f44336;              /* Red */
    --text-main: #ffffff;           /* Main text */
    --text-muted: #a0a0a0;          /* Muted text */
    --text-light: #e0e0e0;          /* Light text */
    --header-bg: #051429;           /* Header background */
    --border-color: #333;           /* Border color */
}
```

## 📱 Responsive Design Tips

### Mobile First
- Write styles for mobile first
- Use `@media (min-width: 768px)` for tablet+
- Use `@media (min-width: 1024px)` for desktop

### Breakpoints
- Mobile: 480px and below
- Tablet: 481px - 768px
- Desktop: 769px and above

### Grid Responsiveness
```php
<!-- Automatically adapts:
- Desktop (3+ cols): 4 columns
- Tablet (2 cols): 2 columns
- Mobile (1 col): 1 or 2 columns
-->
<div class="grid grid-4">...</div>
```

## 🔧 JavaScript Functions

### Format Currency
```javascript
formatCurrency(1999.50); // Returns "1,999.50"
```

### Show Alert
```javascript
showAlert('success', 'Operation completed!');
showAlert('error', 'Something went wrong');
showAlert('warning', 'Are you sure?');
showAlert('info', 'Information message');
```

### Mobile Menu
The menu is automatically controlled by `ui.js`:
```javascript
// Burger icon click toggles menu
// Links in menu close it automatically
// Close button available in menu
```

## 🎯 Component Dependencies

- **Carousel**: `carousel.js` (auto-loads on page with `.hero-carousel`)
- **UI Utilities**: `ui.js` (provides Modal, showAlert, formatCurrency)
- **Checkout**: `checkout.js` (voucher application, payment)
- **CSS**: `style.css` (all styling and animations)

## ⚡ Performance Tips

1. Use `class="grid grid-auto"` for flexible product grids
2. Lazy-load images for product cards
3. Minify CSS/JS in production
4. Use CSS Grid instead of flexbox for large layouts
5. Avoid inline styles where possible (use CSS classes)

## 🔐 Security Notes

- Always use `<?= esc($variable) ?>` for user input
- Always include `<?= csrf_field() ?>` in forms
- Use `session()->get()` for accessing session data
- Validate on both client and server side

## 📖 Common Routes

- `/` - Homepage
- `/products` - Product listing
- `/product/{sku}` - Product detail
- `/checkout` - Checkout page
- `/login` - Login page
- `/register` - Registration page
- `/account` - User dashboard
- `/admin/dashboard` - Admin dashboard
- `/admin/products` - Product management
- `/admin/vouchers` - Voucher management

## 🚀 Workflow Example

```php
<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div style="padding: 30px 15px;">
    <h1>Page Title</h1>
    
    <div class="grid grid-3">
        <?php foreach ($items as $item): ?>
            <div class="card">
                <h3><?= esc($item['name']) ?></h3>
                <p><?= esc($item['description']) ?></p>
                <a href="#" class="btn btn-primary">Action</a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?= $this->endSection() ?>
```
