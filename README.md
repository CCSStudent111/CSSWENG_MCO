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

After installing the aforementioned software:
###  Using the Command Prompt
For the user to check: **(a)** if the software is installed correctly, and/or **(b)** to check the version, type either of this to the command prompt:\
`composer --version`
or
`php -v`

Then, 



*old*\
MCO for CSSWENG 

run command for laravel

composer install (if php artisan command does not exist)

create .env file copy .env.example (if .env file does not exist)

php artisan key:generate (if key does not exist)

php artisan migrate:fresh --seed (setup tables for database)

php artisan serve (run localhost)

if frontend vue run npm run dev with php artisan serve
