# Auth system API

The project is a web application with user registration and authentication functionality, including email verification and password recovery. The system has roles with administrator rights. Three languages are supported for the interface. Users can update their personal information and delete their profile through the settings.

To configure the project, specify the database communication settings in the .env configuration file.

During installation, an administrator user will be created. Login details:  
**Email**: test@example.com  
**Password**: password

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
