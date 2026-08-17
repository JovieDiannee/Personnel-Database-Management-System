# Personnel Database Management System (PDMS)

## Department of Education – Schools Division of Leyte

The **Personnel Database Management System (PDMS)** is a web-based personnel information management system designed to centralize and organize employee records and personnel-related information for the **Department of Education (DepEd), Schools Division of Leyte**.

The system provides a structured platform for managing personnel data, supporting administrative transactions, and improving the retrieval and management of employee information.

---

## Table of Contents

- [Project Overview](#project-overview)
- [Objectives](#objectives)
- [Main Features](#main-features)
- [User Access and Security](#user-access-and-security)
- [Technology Stack](#technology-stack)
- [System Requirements](#system-requirements)
- [Installation](#installation)
- [Environment Configuration](#environment-configuration)
- [Database Setup](#database-setup)
- [Frontend Assets](#frontend-assets)
- [Running the System](#running-the-system)
- [Production Build](#production-build)
- [Git and GitHub Workflow](#git-and-github-workflow)
- [Hostinger Deployment](#hostinger-deployment)
- [Project Structure](#project-structure)
- [Troubleshooting](#troubleshooting)
- [Security Notes](#security-notes)
- [Project Information](#project-information)

---

## Project Overview

PDMS is intended to provide a centralized web-based environment for personnel data management.

The system is designed to help authorized personnel:

- Manage employee/personnel records
- Retrieve personnel information efficiently
- Maintain organized personnel data
- Manage HR-related information and transactions
- Support administrative monitoring and reporting
- Provide controlled access to system functions
- Reduce dependence on manually maintained personnel records

---

## Objectives

### General Objective

To design and develop a web-based **Personnel Database Management System (PDMS)** for the Department of Education – Schools Division of Leyte that centralizes personnel records, supports personnel-related processes, improves information retrieval, and provides controlled access to personnel information.

### Specific Objectives

The system aims to:

1. Centralize personnel information in a structured database.
2. Improve the accessibility and retrieval of employee records.
3. Support the management and updating of personnel information.
4. Provide user authentication and access control.
5. Support HR-related transactions and personnel data management.
6. Improve the efficiency of administrative record management.
7. Provide a responsive and user-friendly web interface.

---

## Main Features

### 1. Authentication

- Login
- Logout
- Password management
- Remember-me functionality
- Password reset functionality
- Session-based authentication

### 2. User Access Control

The system supports controlled access to system functionality based on configured user roles and permissions.

### 3. Personnel Management

Provides functionality for managing personnel information, employee profiles, and related records.

### 4. Data Management

Provides interfaces for organizing and maintaining personnel-related data.

### 5. Dashboard

Provides an overview of important system information and personnel-related data.

### 6. HR-Related Transactions

Designed to support personnel and HR-related transactions and administrative processes.

---

## User Access and Security

PDMS is intended for **authorized personnel only**.

Users should:

- Keep their usernames/email addresses confidential.
- Keep passwords confidential.
- Never share authentication credentials.
- Log out after using the system, especially on shared computers.
- Access only information and functions authorized for their role.

Sensitive personnel information should only be accessed and processed by authorized personnel.

---

## Technology Stack

### Backend

- PHP
- Laravel
- Laravel Blade
- Laravel Authentication
- Laravel Eloquent ORM

### Frontend

- HTML
- CSS
- JavaScript
- Tailwind CSS
- Vite

### Database

- MySQL / MariaDB
- Laravel Migrations
- Eloquent Models

### Development Tools

- Visual Studio Code
- Git
- GitHub
- Composer
- Node.js and npm
- XAMPP for local development

### Production Hosting

- Hostinger
- Git-based deployment
- SSH access

---

## System Requirements

Before installing the project, make sure the development environment has:

- PHP
- Composer
- Node.js
- npm
- MySQL or MariaDB
- Git
- Web server
- Required PHP extensions for Laravel and installed Composer packages

For local Windows development, **XAMPP** may be used to provide PHP, Apache, and MySQL.

---

# Installation

## 1. Clone the Repository

```bash
git clone https://github.com/JovieDiannee/Personnel-Database-Management-System.git
cd Personnel-Database-Management-System
```

## 2. Install PHP Dependencies

```bash
composer install
```

## 3. Install Node Dependencies

```bash
npm install
```

## 4. Create the Environment File

### Windows PowerShell

```powershell
Copy-Item .env.example .env
```

### Linux/macOS

```bash
cp .env.example .env
```

## 5. Generate the Application Key

```bash
php artisan key:generate
```

---

# Environment Configuration

Open the `.env` file and configure the application.

Example:

```env
APP_NAME="Personnel Database Management System"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=root
DB_PASSWORD=
```

**Never commit the `.env` file to GitHub.**

---

# Database Setup

Create a MySQL database for the project and update the database settings in `.env`.

Run migrations:

```bash
php artisan migrate
```

If the project requires seed data:

```bash
php artisan db:seed
```

or:

```bash
php artisan migrate --seed
```

Always verify the current migrations and seeders before using them on a production database.

---

# Frontend Assets

PDMS uses Vite to compile frontend assets.

For development:

```bash
npm run dev
```

For production:

```bash
npm run build
```

The production files are generated in:

```text
public/build/
```

The generated `manifest.json` and compiled CSS/JavaScript files are required by Laravel when using Vite in production.

---

# Running the System

## Laravel Development Server

```bash
php artisan serve
```

The system will normally be available at:

```text
http://127.0.0.1:8000
```

## Vite Development Server

In another terminal:

```bash
npm run dev
```

---

# Production Build

Before deploying frontend changes:

```bash
npm run build
```

Verify that the build completes successfully and that `public/build/` contains the current compiled assets.

---

# Git and GitHub Workflow

## Check Status

```bash
git status
```

## Add Changes

```bash
git add .
```

## Commit Changes

```bash
git commit -m "Update system"
```

## Push to Main

```bash
git push origin main
```

## Pull Latest Changes

```bash
git pull origin main
```

---

# Hostinger Deployment

The production system is hosted through Hostinger and connected to the GitHub repository.

## Recommended Deployment Workflow

### 1. Test Locally

Make and test your changes locally.

### 2. Build Vite Assets

```bash
npm run build
```

### 3. Check Git

```bash
git status
```

### 4. Commit and Push

```bash
git add .
git commit -m "Update system"
git push origin main
```

### 5. Redeploy on Hostinger

Open the Hostinger Git deployment page and use **Redeploy** after the latest commit is available on GitHub.

### 6. Clear Laravel Cache

Using SSH from the Laravel project directory:

```bash
php artisan optimize:clear
```

You may also use:

```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

### 7. Verify Vite Build

Check:

```text
public/build/
```

Make sure `manifest.json` and the current CSS/JavaScript assets are present.

---

# Important Vite Deployment Note

If the local system has a newer Vite build but production still displays the old design, check:

1. `npm run build` completed successfully.
2. The latest build files are included in deployment.
3. The latest GitHub commit is deployed.
4. Hostinger is deploying the correct branch.
5. `public/build/manifest.json` is current.
6. Laravel caches have been cleared.
7. Browser cache is not displaying an old asset.

Force-refresh the browser with:

```text
Ctrl + F5
```

or:

```text
Ctrl + Shift + R
```

---

# Project Structure

```text
Personnel-Database-Management-System/
│
├── app/
│   ├── Http/
│   │   └── Controllers/
│   └── Models/
│
├── bootstrap/
├── config/
│
├── database/
│   ├── migrations/
│   ├── seeders/
│   └── factories/
│
├── public/
│   ├── build/
│   ├── css/
│   ├── images/
│   └── index.php
│
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
│       ├── auth/
│       ├── dashboard/
│       ├── data-management/
│       ├── employees/
│       ├── layouts/
│       └── ...
│
├── routes/
│   └── web.php
│
├── storage/
├── tests/
│
├── .env.example
├── .gitignore
├── artisan
├── composer.json
├── package.json
├── tailwind.config.js
└── vite.config.js
```

---

# Troubleshooting

## `npm: command not found`

Verify Node.js and npm:

```bash
node -v
npm -v
```

If Node.js is not available on the production server, build the Vite assets locally with:

```bash
npm run build
```

and deploy the resulting `public/build/` directory according to the project's Git/deployment configuration.

## CSS or JavaScript Is Not Updating

Run:

```bash
npm run build
```

Then:

```bash
git status
git add .
git commit -m "Update frontend build"
git push origin main
```

Redeploy on Hostinger and run:

```bash
php artisan optimize:clear
```

Then hard-refresh the browser.

## Laravel Shows an Old View

```bash
php artisan view:clear
php artisan optimize:clear
```

## Database Connection Error

Check these values in `.env`:

```env
DB_HOST=
DB_PORT=
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```

Then run:

```bash
php artisan config:clear
```

---

# Security Notes

Do not commit these to a public repository:

- `.env`
- Database passwords
- SMTP passwords
- API keys
- Application secrets
- SSH credentials
- Other private credentials

The `.env.example` file should contain placeholders only.

---

# Project Information

**Project Name:** Personnel Database Management System (PDMS)

**Organization:** Department of Education – Schools Division of Leyte

**System Type:** Web-Based Personnel Database Management System

**Primary Purpose:** Personnel data and HR-related information management

**Repository:** Personnel-Database-Management-System

**Technology:** Laravel, MySQL, Vite, Tailwind CSS

**Hosting:** Hostinger

---

# License and Usage

This project is intended for authorized use by the **Department of Education – Schools Division of Leyte**.

Personnel information and other sensitive data must be handled in accordance with applicable government policies, organizational procedures, and data privacy requirements.

---

## Maintainer

**Jovie Gayo**

Personnel Database Management System  
Department of Education – Schools Division of Leyte

---

> **Note:** Update this README whenever major modules, database structures, system requirements, technologies, or deployment procedures change.
