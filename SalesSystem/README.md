Laravel Authentication & OTP Verification System

A Laravel-based authentication system developed using PHP and MySQL, featuring user registration, login, password recovery, and OTP verification via Telegram.

Features

- User Registration: Allows new users to create accounts.
- User Login: Allows registered users to access their accounts.
- Password Hashing: Protects user passwords using secure hashing.
- Form Validation: Validates user input before processing.
- Session Management: Manages authenticated user sessions.
- OTP Verification: Sends verification codes through Telegram.
- Password Recovery: Provides an OTP-based password reset process.

Technologies Used

- PHP: Backend programming language.
- Laravel: PHP web application framework.
- MySQL: Database management system.
- HTML & CSS: Web page structure and styling.
- Bootstrap: User interface structure and responsive layouts.
- Telegram Bot API: Sends OTP verification codes through Telegram.

Requirements

Make sure your environment has the following installed:

- PHP
- Composer
- MySQL
- Laravel-compatible web server
- Telegram Bot Token

Installation

Follow these steps to set up the project:

1. Clone or Download the Project

Download the project files to your local machine.

2. Install Dependencies

Run the following command in the project directory:

composer install

3. Configure Environment

Copy the example environment file:

cp .env.example .env

Update the database settings in your ".env" file:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

4. Generate Application Key

php artisan key:generate

5. Run Database Migrations

php artisan migrate

6. Configure Telegram Bot

Add your Telegram Bot Token and the required Telegram settings to your environment configuration.

7. Start the Development Server

php artisan serve

Open the application in your browser using the local URL displayed in your terminal.

Project Purpose

This system provides reusable authentication and OTP verification functionality for Laravel applications. It can serve as a starting point for developers who want to integrate user authentication, account management, and Telegram-based verification into their projects.

Customization

Developers can customize the authentication logic, user interface, and verification process according to their project requirements.

License

Please refer to the license terms provided with this product.