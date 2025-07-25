# Document Management System

> **Note:** These instructions are for a fresh Windows installation (not tested for MacOS or Linux).

## Prerequisites

**Required versions:**
- [XAMPP 8.2.12 (includes PHP 8.2.12)](https://sourceforge.net/projects/xampp/files/XAMPP%20Windows/8.2.12/xampp-windows-x64-8.2.12-0-VS16-installer.exe)  
  PHP is bundled with XAMPP. PHP version must match XAMPP's version (required: PHP 8.2.12).
- [Composer 2.7+](https://getcomposer.org/Composer-Setup.exe)
- [Laravel 11](https://laravel.com/docs/11.x/installation) (installed globally via Composer)
- [Git](https://git-scm.com/download/win)
- [Notepad++](https://notepad-plus-plus.org/downloads/)
- [7-Zip](https://www.7-zip.org/download.html) or any zip extractor (e.g., WinRAR)
- [Node.js (includes npm)](https://nodejs.org/) (any current stable version)

## Step 1: Install Prerequisite Software

1. Install Git, 7-Zip, Notepad++, and Node.js (npm).
2. Download and install XAMPP 8.2.12 (includes PHP 8.2.12).

> Only install PHP separately if you are not using XAMPP.

## Step 2: Set Up PHP Path

- XAMPP does not automatically add PHP to your system PATH.
- Open System Environment Variables and add the XAMPP PHP folder (e.g., `C:\xampp\php`) to your PATH.
  - Skip this step if you already have PHP installed elsewhere and available in PATH.

> If PHP is only for this project, just add XAMPP's PHP to PATH.

## Step 3: Enable PHP Extensions

> If you are using XAMPP, these extensions are usually enabled by default.  
> If you installed PHP manually (not using XAMPP), open your `php.ini` and make sure the following lines are uncommented (no leading `;`):

```
extension=curl
extension=fileinfo
extension=gettext
extension=mbstring
extension=exif
extension=mysqli
extension=pdo_mysql
extension=pdo_sqlite
```

For XAMPP users, double-check by searching these lines in `C:\xampp\php\php.ini`.  
For manual PHP installations, edit `php.ini` as needed and restart your web server or PHP process after saving.

## Step 4: Install Composer

Download and run [Composer-Setup.exe (2.7+)](https://getcomposer.org/download/).

## Step 5: Install Laravel 11 Installer

Open CMD or PowerShell and run:
```sh
composer global require laravel/installer
```
Make sure `laravel -v` outputs version 11.x.x.

## Step 6: Verify Installations

Check that everything is installed correctly:
```sh
npm -v
composer -v
laravel -v
php -v
```
- Composer should be 2.7+
- Laravel should be 11.x
- PHP should be 8.2.12

## Step 7: Clone the Project

Clone the project using Git:
```sh
git clone <repository-url>
```
Enter the project directory:
```sh
cd DMS
```
Create a new `.env` file by copying the provided `.env.example`:
```sh
copy .env.example .env
```
(or use `cp .env.example .env` in Git Bash)

## Step 8: Install PHP Dependencies

```sh
composer install
```

## Step 9: Generate Application Key

```sh
php artisan key:generate
```

## Step 10: Start XAMPP Services

Open XAMPP Control Panel and start Apache and MySQL.

## Step 11: Run Database Migrations & Seeders

Run these in order in the command terminal:
```sh
php artisan migrate
php artisan migrate:fresh
php artisan migrate:fresh --seed
```

## Step 12: Serve the Application

```sh
php artisan serve
```
Open [http://localhost:8000](http://localhost:8000) in your browser.

(Keep `php artisan serve` running)

## Step 13: Run the Frontend (Vue)

In a separate terminal, run:
```sh
npm install
npm run dev
```

## Step 14: Storage Link

If you encounter storage errors, run:
```sh
php artisan storage:link
```

Result: There should be two running terminals—one for the frontend and one for the backend.  
Open [http://localhost/phpmyadmin](http://localhost/phpmyadmin) and [http://localhost:8000](http://localhost:8000).

## Optional: Increase File Upload Size (XAMPP)

To increase upload/file size limits:

Open `php.ini` and set these values:
```
memory_limit = 512M
post_max_size = 100M
upload_max_filesize = 100M
```
Restart Apache after making changes.

---

## Troubleshooting

- If you get database connection errors, double-check your `.env` database credentials.
  - If you have MySQL Workbench installed, it may affect your database credentials. Set `DB_PASSWORD` to empty (`DB_PASSWORD=`) if needed.
- Ensure MySQL is running in XAMPP.
- If XAMPP errors occur, open Task Manager, end any `mysqld.exe` processes, and restart MySQL from XAMPP.
- For "port in use" errors, see which app is using the port (`netstat -ano | findstr :3306`) and end the process.
- contact grass for errors.

---
