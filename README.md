# Winbreaker E-commerce Website

A full-featured e-commerce website built with Laravel featuring product management, shopping cart, order processing, and admin panel.

## Features

- ✅ User Registration, Login, and Logout
- ✅ Product Catalog with Categories (Shirts, Caps, Shorts, Shoes, etc.)
- ✅ Shopping Cart Functionality
- ✅ Order Management
- ✅ Admin Panel for Product and Category Management
- ✅ Image Upload for Products
- ✅ Responsive Design (Mobile-friendly)
- ✅ Admin Account with Protected Routes

## Requirements

- PHP >= 8.2
- Composer
- Node.js and npm
- SQLite (or MySQL/PostgreSQL)

## Installation

1. **Install PHP Dependencies**
   ```bash
   composer install
   ```

2. **Install Node Dependencies**
   ```bash
   npm install
   ```

3. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Setup**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

5. **Storage Link**
   ```bash
   php artisan storage:link
   ```

6. **Build Assets**
   ```bash
   npm run build
   ```

## Default Admin Account

After running the seeder, you can login with:
- **Email:** admin@winbreaker.com
- **Password:** admin123

**Important:** Change the admin password after first login!

## Running the Application

```bash
php artisan serve
```

Then visit `http://localhost:8000` in your browser.

For development with hot reload:
```bash
npm run dev
```

## Admin Panel

Access the admin panel at `/admin/dashboard` (requires admin login).

### Admin Features:
- Dashboard with statistics
- Manage Products (Create, Edit, Delete, Upload Images)
- Manage Categories (Create, Edit, Delete)
- View Orders

## Project Structure

- **Models:** User, Product, Category, CartItem, Order, OrderItem
- **Controllers:** AuthController, HomeController, ProductController, CartController, OrderController
- **Admin Controllers:** DashboardController, ProductController, CategoryController
- **Views:** Blade templates with Tailwind CSS for responsive design
- **Middleware:** AdminMiddleware for protecting admin routes

## Categories Included

- Shirts
- Caps
- Shorts
- Shoes
- Pants
- Jackets
- Accessories

## Notes

- Product images are stored in `storage/app/public/products`
- Make sure to run `php artisan storage:link` to create a symbolic link
- All views are responsive and mobile-friendly
- The admin can upload product images when creating/editing products
