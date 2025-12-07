# PlayPass UI Components & Pages

This document outlines the complete UI implementation for the PlayPass e-commerce platform.

## 📁 File Structure

```
app/Views/
├── layouts/
│   └── main.php                    # Master layout template (header, footer, CSS)
├── home/
│   └── index.php                   # Homepage with hero carousel & products
├── auth/
│   ├── login.php                   # Enhanced login form
│   └── register.php                # Enhanced registration form
├── products/
│   ├── index.php                   # Product listing/browse page
│   └── show.php                    # Product detail page
├── user/
│   └── index.php                   # User dashboard
├── articles/
│   └── show.php                    # Article detail page
├── admin/
│   ├── dashboard.php               # Admin dashboard
│   ├── products/
│   │   └── index.php               # Product management
│   └── vouchers/
│       └── index.php               # Voucher management
├── cells/
│   ├── product_card.php            # Reusable product card
│   └── article_card.php            # Reusable article card

public/assets/
├── css/
│   └── style.css                   # Complete CSS framework (630+ lines)
├── js/
│   ├── carousel.js                 # Hero carousel functionality
│   ├── checkout.js                 # Checkout logic
│   └── ui.js                       # General UI utilities
```

## 🎨 CSS Framework Features

The `style.css` file includes:

- **Color Variables**: Dark theme with primary color (#d8369f), secondary colors, and utility colors
- **Typography**: Consistent heading and text styles
- **Components**:
  - Buttons (primary, secondary, outline, small, large)
  - Forms (inputs, textareas, select, form groups, input groups)
  - Cards and containers
  - Grid system (2, 3, 4 column, auto-fill responsive)
  - Hero carousel with controls
  - Product cards with badges and hover effects
  - Article cards with metadata
  - Alerts (success, error, warning, info)
  - Modals with animations
  - Tables with styling
  - Badges and utility classes

- **Responsive Design**: Mobile-first approach with breakpoints at 768px and 480px
- **Animations**: Smooth transitions, carousel animations, modal slide-up effects
- **Accessibility**: Semantic HTML, proper focus states, ARIA labels

## 🖼️ Page Components

### 1. **Homepage** (`home/index.php`)
   - Hero carousel with 3 rotating slides
   - Auto-play (5 second interval) with pause on hover
   - Navigation dots and arrow buttons
   - New Arrivals section (product grid)
   - Featured products section
   - Latest stories/articles section

### 2. **Authentication Pages**
   - **Login** (`auth/login.php`):
     - Email/phone number field
     - Password field with remember me option
     - Forgot password link
     - Register link
     - Clean card-based layout
   
   - **Register** (`auth/register.php`):
     - Full name, email, phone, birthdate fields
     - Strong password requirements
     - Interest selection (checkboxes for categories)
     - Terms & conditions agreement
     - Input validation messages

### 3. **Product Pages**
   - **Browse** (`products/index.php`):
     - Search bar
     - Category filters
     - Product status filters
     - Grid layout (auto-fill responsive)
     - Pagination controls
   
   - **Detail** (`products/show.php`):
     - Large product image with gallery
     - Product information (name, description, rating)
     - Price display with discount calculation
     - Points to earn display
     - Buy & Wishlist buttons
     - Product features list
     - Seller information card
     - Related products section

### 4. **User Dashboard** (`user/index.php`)
   - Sidebar with user profile
   - Account balance display
   - Navigation menu (orders, subscriptions, vouchers, settings)
   - Quick stats (total orders, active subscriptions, loyalty points)
   - Recent orders table
   - Active subscriptions with renewal/cancellation options
   - Top-up CTA card

### 5. **Checkout** (`checkout.php`)
   - Product summary card with image
   - Totals breakdown
   - Recipient field (mobile/email)
   - Promo code input with Apply button
   - Payment method selection
   - Terms & conditions checkbox
   - Secure payment confirmation button

### 6. **Article Detail** (`articles/show.php`)
   - Article header with title, author, date
   - Featured image placeholder
   - Full article content
   - Author bio card
   - Related articles section
   - Social sharing buttons

### 7. **Admin Pages**
   - **Dashboard** (`admin/dashboard.php`):
     - 4 key metrics (revenue, orders, users, conversion)
     - Recent orders table
     - Quick action buttons
     - System status indicators
   
   - **Products Management** (`admin/products/index.php`):
     - Product search and filters
     - Table with product details
     - Edit/delete actions
     - Pagination
   
   - **Vouchers Management** (`admin/vouchers/index.php`):
     - Voucher code search
     - Status and type filters
     - Discount display (fixed/percentage)
     - Usage tracking
     - Expiration dates

## 🎯 Key Features

### Reusable Components

**ProductCell** (`Cells/ProductCell.php`):
- Renders product cards with customizable data
- Includes badge logic (BUNDLE, SALE)
- Price formatting
- Points display
- Buy button with product link

**ArticleCell** (`Cells/ArticleCell.php`):
- Renders article cards
- Excerpt generation
- Date formatting
- Read more link

### JavaScript Functionality

**Carousel** (`js/carousel.js`):
```javascript
- Auto-play with 5-second interval
- Manual navigation (dots, arrow buttons)
- Pause on hover
- Responsive slide transitions
- Accessible controls
```

**UI Utilities** (`js/ui.js`):
```javascript
- Mobile menu toggle
- Modal open/close handlers
- Currency formatting
- Alert display system
- Smooth scroll for anchors
- Form utilities
```

### Grid System

```php
// 2-column grid
<div class="grid grid-2">...</div>

// 3-column grid
<div class="grid grid-3">...</div>

// 4-column grid
<div class="grid grid-4">...</div>

// Auto-fill with 160px minimum
<div class="grid grid-auto">...</div>

// Responsive behavior at 768px and 480px
```

## 🎭 Design Decisions

1. **Dark Theme**: Professional gaming-focused aesthetic with #0c0c0c background
2. **Primary Color**: Bold pink (#d8369f) for CTAs and highlights
3. **Responsive Design**: Mobile-first approach with graceful desktop enhancement
4. **Cards Over Flat Design**: Better visual hierarchy and content separation
5. **Grid System**: Flexible auto-fill grid for products adapts to screen size
6. **Carousel**: Hero carousel for featured content with auto-play
7. **Typography**: Large, bold headlines for impact; readable body text
8. **Spacing**: Consistent 15px padding for mobile, 30px+ for sections
9. **Interactive Feedback**: Hover effects, smooth transitions, visual states

## 🔧 Usage Examples

### Adding a New Product Card
```php
<?= view_cell('App\Cells\ProductCell::renderCard', ['product' => $product]) ?>
```

### Opening a Modal
```javascript
Modal.open('myModalId');
```

### Displaying an Alert
```javascript
showAlert('success', 'Purchase completed!');
```

### Creating a Grid
```html
<div class="grid grid-3">
    <!-- 3 equal columns, responsive -->
</div>
```

## 📱 Responsive Breakpoints

- **Mobile**: 480px and below
- **Tablet**: 481px - 768px
- **Desktop**: 769px and above

Grid adjustments:
- Desktop: Original (grid-3 = 3 cols, grid-4 = 4 cols)
- Tablet: 2 columns (grid-2, grid-3, grid-4 all become 2 cols)
- Mobile: 1 column or 2 columns (grid-auto becomes 2 cols)

## 🚀 Next Steps

To fully populate the UI with data:
1. Create controllers for each page route
2. Fetch data from models and pass to views
3. Implement search and filter functionality
4. Add form handling and validation
5. Integrate payment gateway endpoints
6. Add user authentication checks
7. Implement real-time notifications
8. Add analytics tracking

## 🎓 Best Practices Used

- **Semantic HTML**: Proper heading hierarchy, form labels
- **Accessibility**: ARIA labels, focus states, color contrast
- **Performance**: Minimal CSS, no unnecessary animations
- **Maintainability**: Reusable components, consistent naming
- **Security**: CSRF protection in forms, input escaping
- **Mobile-First**: Mobile layout as foundation, enhanced for larger screens
