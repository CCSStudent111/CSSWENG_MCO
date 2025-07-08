<h1 align="center">CSSWENG_MCO</h1>

## About
The **Document Management System** (hereby called DMS) is a software used to manage, store, organize and track electronic documents that is captured through the use of a document scanner.\
This version of DMS is created using the following:

**Backend**
- Laravel (v.11)
- PHP (v 8.2.12)
- JavaScript

**Frontend**
- Vuetify (v 3.8.11)
- CSS

## Getting Started / Installation
To get started, you may need to have the following installed on your machine:
- [Composer](https://getcomposer.org/), a dependency manager for PHP,
- [Laravel](https://laravel.com/docs/12.x/installation)(at least v.11 or newer),
- [PHP](https://www.php.net/manual/en/install.php)(at least v.8.2 or newer).

/*Optional:*
- For self-hosting, download [XAMPP](https://www.apachefriends.org/download.html).

/After installing the aforementioned software:
###  Using the Command Prompt
For the user to check: **(a)** if the software is installed correctly, and/or **(b)** to check the version, type either of this to the command prompt (hereby called *cmd*):\
`composer --version`
or
`php -v`

/After that, you need to create an **.env** file. To streamline this process, if you are using VSCode and have access in the DMS repository:
1. Type `composer install` in the terminal (**NOTE: only use this command if the `php artisan` command does not exist**), it will generate an **.env** file, specifically `.env.example`.
2. If `.env` file doesn't exist, copy the contents of `.env.example` and create your own `.env` file.
3. If the key doesn't exist, type `php artisan key:generate`. It will generate a key in the `.env` file.
4. 





*Side Note:* If your PHP is a fresh install:
- create a file called *php.ini*,
- remove *;extensions=fileinfo*\
In the off-change that the user gets a error when creating/downloading a Laravel Project (error 28), **disable IPv6**. To disable *IPv6*:
1. Open the **Control Panel**,
2. Open **Network and Internet**,
3. Open **Network and Sharing Center**,
4. Click **Change Adapter Setting**,
5. Right-click your connection and go to **Properties**, and
6. Uncheck the box next to Internet Protocol Version 6 (TCP/IPv6) to disable it.





*old*\
MCO for CSSWENG 

run command for laravel

composer install (if php artisan command does not exist)

create .env file copy .env.example (if .env file does not exist)

php artisan key:generate (if key does not exist)

php artisan migrate:fresh --seed (setup tables for database)

php artisan serve (run localhost)

if frontend vue run npm run dev with php artisan serve
