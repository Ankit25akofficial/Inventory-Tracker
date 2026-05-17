# 📦 InvenTrack — QR-Based Inventory Management System

<div align="center">

![Laravel](https://img.shields.io/badge/Laravel-9.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.0+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

**A premium, dark-themed inventory management system with QR code scanning, role-based access control, and real-time stock tracking.**

</div>

---

## ✨ Features

### 🔐 Authentication & Roles
- **Glassmorphic Login Page** — sleek dark-themed login screen
- **Role-Based Access Control** — two roles with different permissions:
  - `Admin` — full access (products, categories, suppliers, reports, QR printing)
  - `Staff` — limited access (view products, update stock via scanner, view inventory)

### 📊 Dashboard
- Real-time stats: total products, total stock value, low-stock alerts, recent activity
- Interactive charts and animated stat cards
- Recent stock movement feed

### 📦 Product Management *(Admin only)*
- Full CRUD for products (create, read, update, delete)
- AI-generated product photography (studio-lit 8K images per product)
- Categories and Suppliers management
- Auto-generated QR codes on product creation

### 📷 QR Code Scanner
- **Upload Image** tab (default) — drag-and-drop or browse a QR image
- **Camera Scan** tab — live webcam scanning
- Real-time product lookup with animated result card
- Supports three scan actions: **Check Info**, **Stock In**, **Stock Out**
- Session stats (total scans / successful / failed)

- <img width="1503" height="914" alt="image" src="https://github.com/user-attachments/assets/938122c0-274b-472d-8ba5-6b13702bafe4" />


### 📋 Inventory Management
- View all stock levels across all products
- Quick stock update with quantity and remarks
- Invoice generation per stock movement
- Filterable and sortable inventory table

### 📈 Reports *(Admin only)*
- Full inventory value report
- Export to **PDF** (via DomPDF) and **Excel** (via Maatwebsite Excel)
- Low stock alerts'
- <img width="1907" height="904" alt="image" src="https://github.com/user-attachments/assets/d5d9fb29-51e0-4822-801f-e20b0cef2301" />


### 🖨️ QR Code Printing *(Admin only)*
- Print-ready QR code sheets for all products
- Individual QR download per product (SVG format)
- QR codes encode direct product URLs for instant browser navigation

### Products 
<img width="1894" height="922" alt="image" src="https://github.com/user-attachments/assets/5d697937-d31e-40cf-86ae-92e208bbddb0" />

### Inventory Management

<img width="1462" height="905" alt="image" src="https://github.com/user-attachments/assets/49b42f6f-8317-42ed-a3ef-b1a9f8bfb124" />


## 🛠️ Tech Stack

| Layer | Technology |
|---|---|
| Backend Framework | Laravel 9.x |
| Language | PHP 8.0+ |
| Database | MySQL |
| Auth Scaffolding | Laravel Breeze |
| QR Code Generation | `simplesoftwareio/simple-qrcode` 4.2 |
| QR Code Scanning | `html5-qrcode` (JS library) |
| PDF Export | `barryvdh/laravel-dompdf` 2.0 |
| Excel Export | `maatwebsite/excel` 3.1 |
| Frontend Styling | Custom Vanilla CSS (dark theme) |
| Fonts | Inter + Outfit (Google Fonts) |
| Icons | Font Awesome 6 |

---

## 🚀 Installation

### Prerequisites
- PHP >= 8.0
- Composer
- MySQL
- Node.js & npm
- XAMPP (or any local server)

### Steps

```bash
# 1. Clone the repository
git clone <repo-url> inventory-system
cd inventory-system

# 2. Install PHP dependencies
composer install

# 3. Install Node dependencies
npm install

# 4. Copy the environment file
cp .env.example .env

# 5. Generate application key
php artisan key:generate
```

### Configure `.env`
```env
APP_NAME=InvenTrack
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventory_db
DB_USERNAME=root
DB_PASSWORD=
```

### Finish Setup
```bash
# 6. Run migrations and seed the database
php artisan migrate --seed

# 7. Create storage symlink (for product images & QR codes)
php artisan storage:link

# 8. Build frontend assets
npm run dev

# 9. Start the development server
php artisan serve
```

The app will be available at **http://localhost:8000**

---

## 🔑 Default Credentials

| Role | Email | Password |
|------|-------|----------|
| **Admin** | `...........@gmail.com` | `password` |
| **Staff** | `..........@example.com` | `password` |

> ⚠️ Change these credentials immediately in a production environment.

---

## 📁 Project Structure

```
inventory-system/
├── app/
│   ├── Http/Controllers/
│   │   ├── DashboardController.php
│   │   ├── ProductController.php      # CRUD + QR generation
│   │   ├── InventoryController.php    # Stock updates + invoices
│   │   ├── QrCodeController.php       # Scanner + print
│   │   ├── ReportController.php       # PDF + Excel exports
│   │   ├── CategoryController.php
│   │   └── SupplierController.php
│   ├── Http/Middleware/
│   │   └── RoleMiddleware.php         # RBAC enforcement
│   ├── Models/
│   │   ├── User.php
│   │   ├── Product.php
│   │   ├── Category.php
│   │   ├── Supplier.php
│   │   ├── StockLog.php
│   │   └── QrScan.php
│   └── Exports/
│       └── ProductsExport.php
├── resources/
│   └── views/
│       ├── layouts/
│       │   ├── app.blade.php          # Main dashboard layout
│       │   ├── guest.blade.php        # Login layout (glassmorphic)
│       │   └── navigation.blade.php
│       ├── dashboard/
│       ├── products/
│       ├── inventory/
│       ├── qr/
│       │   ├── scanner.blade.php      # QR scanner page
│       │   └── print.blade.php
│       ├── reports/
│       ├── categories/
│       ├── suppliers/
│       └── profile/
├── routes/
│   ├── web.php                        # All routes + RBAC middleware
│   └── auth.php
├── database/
│   ├── migrations/
│   └── seeders/
│       └── DatabaseSeeder.php         # 25 products + demo data
└── storage/
    └── app/public/
        ├── products/                  # Product images
        └── qrcodes/                   # Generated QR SVGs
```

---

## 🔐 Role Permissions

| Feature | Admin | Staff |
|---------|:-----:|:-----:|
| Dashboard | ✅ | ✅ |
| View Products | ✅ | ✅ |
| Create / Edit / Delete Products | ✅ | ❌ |
| Manage Categories | ✅ | ❌ |
| Manage Suppliers | ✅ | ❌ |
| View Inventory | ✅ | ✅ |
| Update Stock | ✅ | ✅ |
| QR Scanner | ✅ | ✅ |
| Download / Print QR Codes | ✅ | ❌ |
| View Reports | ✅ | ❌ |
| Export PDF / Excel | ✅ | ❌ |

---

## 📷 QR Code System

QR codes are automatically generated when a product is created or updated. Each QR code encodes a **direct URL** to the product's detail page:

```
http://your-domain.com/products/{id}?sku=KEY-001
```

This means:
- 📱 **Scanning with phone camera** instantly opens the product page in the browser
- 🔍 **Scanning via the in-app scanner** looks up the product and updates stock

---

## 📝 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

<div align="center">
Built with ❤️ using Laravel &amp; a custom dark dashboard theme.
</div>
