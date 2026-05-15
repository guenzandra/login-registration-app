<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/yourusername/login-registration-app/actions"><img src="https://github.com/yourusername/login-registration-app/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# 🔐 Login & Registration App with Admin CRUD

A **secure, production-ready** authentication system built with Laravel featuring role-based access control, password reset functionality (OTP via email), and a complete admin dashboard for user management.

---

## ✨ Features

| Category | Features |
|----------|----------|
| **Authentication** | User registration, secure login, session-based auth, logout |
| **Password Reset** | 6-digit OTP code, 15-minute expiration, email integration ready |
| **Admin Dashboard** | Complete CRUD operations (Create, Read, Update, Delete users) |
| **Role Management** | Admin, Moderator, User roles with different permissions |
| **Account Status** | Active, Inactive, Banned statuses |
| **Security** | CSRF protection, XSS prevention, SQL injection protection (Eloquent), password hashing (bcrypt) |
| **UI Framework** | Tailwind CSS / Bootstrap 5 responsive design |

---

## 🛠️ Tech Stack

- **Backend:** Laravel 10+ (PHP 8.1+)
- **Database:** MySQL / MariaDB
- **Authentication:** Laravel Session + Auth Middleware
- **Password Hashing:** Bcrypt (Laravel Hash facade)
- **Frontend:** Blade templates + Tailwind CSS / Bootstrap 5
- **Form Protection:** CSRF tokens

---

## 📦 Requirements

```bash
PHP >= 8.1
Composer >= 2.0
MySQL >= 5.7 / MariaDB >= 10.2
Node.js & NPM (optional, for frontend assets)