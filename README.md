# Laravel Authentication & OTP Verification System

A secure authentication and verification system built with **PHP**, **Laravel**, and **MySQL**, featuring One-Time Password (OTP) verification (including Telegram OTP integration).

## 🚀 Features
* **User Authentication**: Secure Login, Registration, and Password Reset workflows.
* **OTP Verification**: Multi-factor verification security using OTP codes.
* **Telegram Integration**: Automated OTP delivery via Telegram Bot API.
* **Role & Session Management**: Built-in user role handling and session security.

## 🛠️ Technologies Used
* **Backend**: PHP, Laravel Framework
* **Database**: MySQL
* **Tools**: Git, Composer, Termux

## ⚙️ Installation & Setup
1. Clone the repository:
   ```bash
   git clone [https://github.com/3lmugtaba/Laravel-Authentication-OTP-Verification-System.git](https://github.com/3lmugtaba/Laravel-Authentication-OTP-Verification-System.git)

2. Navigate to the project directory:
   ```bash
   cd Laravel-Authentication-OTP-Verification-System/SalesSystem
   

3. Install dependencies:
   ```bash
   composer install
   
4. Copy the environment file and configure your database:
   ```bash
   cp .env.example .env
   
5. Generate application key:
   ```bash
   php artisan key:generate

6. Run migrations:
   ```bash
   php artisan migrate
