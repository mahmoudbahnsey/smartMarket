# 🛒 SmartMarket — Intelligent Multi-Branch E-Commerce System

## 📌 Project Overview
SmartMarket is a professional multi-branch e-commerce platform developed using **Laravel 12** and **MySQL**.  
The system is designed to help retail businesses manage products, inventories, customer orders, and branch operations through a centralized and secure web application.

The platform supports multiple store branches with independent inventory management while providing administrators with complete monitoring and reporting capabilities.

---

# 🚀 Features

## 👤 Authentication & Authorization
- User Registration & Login
- Role-Based Access Control (Admin, Branch Manager, Customer)
- Session Management
- Secure Password Validation
- Middleware Protection

---

## 🏪 Multi-Branch Management
- Multiple Store Branches
- Branch-Based Inventory Management
- Stock Tracking Per Branch
- Branch Dashboard

---

## 🛍️ Product Management
- Add/Edit/Delete Products
- Product Categories
- Product Images Upload
- Sale Pricing
- Search & Filtering

---

## 🛒 Shopping System
- Shopping Cart
- Quantity Management
- Wishlist Support
- Checkout System
- Order Tracking
- Order History

---

## 📦 Inventory System
- Inventory Per Branch
- Quantity Tracking
- Low Stock Alerts
- Inventory Updates

---

## 📊 Reporting & Analytics
- Revenue Reports
- Daily Sales Statistics
- Branch Performance Reports
- Top-Selling Products

---

# 🔐 Security Features
- SQL Injection Prevention
- XSS Protection
- CSRF Protection
- Secure File Upload Validation
- Security Headers Middleware
- Rate Limiting for Login Attempts

---

# 🧱 Technologies Used

| Technology | Purpose |
|------------|----------|
| Laravel 12 | Backend Framework |
| PHP 8.2 | Server-Side Programming |
| MySQL 8 | Database Management |
| Blade Engine | Frontend Templating |
| HTML5/CSS3 | User Interface |
| JavaScript ES6 | Interactive Features |
| XAMPP | Local Development Environment |
| Font Awesome | Icons |
| Google Fonts | Typography |

---

# 🏗️ System Architecture
The project follows the **MVC Architecture**:

- **Model Layer** → Database Operations
- **View Layer** → Blade Templates & UI
- **Controller Layer** → Business Logic

---

# 🗄️ Database Structure

## Main Tables
- Users
- Products
- Categories
- Orders
- Branches
- Inventories
- Reviews
- Cart Items

---

# 👥 User Roles

## 🔴 Admin
- Full System Access
- Product Management
- User Management
- Branch Management
- Reports & Analytics

## 🟠 Branch Manager
- Branch Inventory Management
- View Branch Orders
- Monitor Stock Levels

## 🟢 Customer
- Browse Products
- Add to Cart
- Place Orders
- Track Orders

---

# ⚙️ Installation Guide

## 1️⃣ Clone Repository
```bash
git clone https://github.com/YOUR_USERNAME/SmartMarket.git
