# Auth system API

The project is a web application with user registration and authentication functionality, including email verification and password recovery. The system has roles with administrator rights. Three languages are supported for the interface. Users can update their personal information and delete their profile through the settings.

To configure the project, you need to specify the settings for database communication in the configuration file located at .env

## Project Setup

```sh
composer install
```

### Create tables in database

```sh
php artisan migrate
```

### Start your local development server

```sh
php artisan serve
```
