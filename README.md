# POS (Point of Sale) System

A modern Point of Sale system built with Laravel 12, Bootstrap 5, and Spatie Permissions.

## Features

- **Authentication** - Role-based login system (Super Admin, Manager, Cashier)
- **Dashboard** - Sales statistics, recent transactions, top products
- **Staff Management** - Create, edit, delete staff members with role assignment
- **Product Management** - Products with variants (size, color, price, stock)
- **Category Management** - Product categorization
- **Customer Management** - Customer database
- **POS Screen** - Quick billing with product search, customer lookup, cart management
- **Sales Tracking** - View and manage all transactions
- **Reports** - Sales summary, top products, inventory reports

## User Roles & Permissions

### Super Admin
- Full access to all features
- Can manage staff roles and permissions

### Manager
- Can manage sales, view reports
- Can manage products, categories, customers
- Can view staff (no management)

### Cashier
- Can process sales via POS
- Can add/view customers
- Cannot access reports, staff management

## Technology Stack

- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Bootstrap 5, Bootstrap Icons
- **Database**: MySQL
- **Authentication**: Laravel Auth with Spatie Permissions
- **JavaScript**: Vanilla JS with SweetAlert2

## Installation

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js & NPM
- MySQL

### Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/ransharirodrigo/POS
   cd POS
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   ```
   
   Edit `.env` file and configure your database:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=pos
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Create the database**
   ```sql
   CREATE DATABASE pos;
   ```

5. **Generate application key**
   ```bash
   php artisan key:generate
   ```

6. **Run migrations**
   ```bash
   php artisan migrate
   ```

7. **Seed the database** (creates default users and data)
   ```bash
   php artisan db:seed
   ```
   
   Default login credentials:
   - Username: `admin`
   - Password: `password`

8. **Link storage**
   ```bash
   php artisan storage:link
   ```

9. **Build assets**
   ```bash
   npm run dev   # for development with hot reload
   # or
   npm run build # for production
   ```

10. **Start the server**
   ```bash
   php artisan serve
   ```

11. **Access the application**
   Open your browser and go to: `http://127.0.0.1:8000`

## Project Structure

```
app/
├── Http/
│   ├── Controllers/     # Controllers for all features
│   └── Middleware/      # Custom middleware
├── Models/              # Eloquent models
├── Providers/          # Service providers
database/
├── migrations/          # Database migrations
└ seeders/            # Seed data for testing
resources/
├── views/              # Blade templates
lang/                   # Translation files (root)
public/
├── css/                # Custom styles
└ js/                 # JavaScript files
routes/
└ web.php             # Application routes
```