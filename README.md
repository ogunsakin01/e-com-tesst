# Part 1: Simple Ecom API && Part 2: PHP Code Test
![GitHub Actions](https://github.com/ogunsakin01/e-com-test/actions/workflows/laravel.yml/badge.svg)
## Installation

### Prerequisites
Make sure you have the following installed on your system before proceeding with the installation:

- PHP ^8.0
- Composer
- Laravel 10
- Laravel Valet (optional, for local development with Valet)

### General Installation Steps

#### 1. Clone the repository to your local machine
```shell
git clone https://github.com/ogunsakin01/e-com-test.git
```

#### 2. Change into the project directory
```shell
cd e-com-test
```

#### 3. Install project dependencies using Composer:
```shell
composer install
```

#### 4. Copy the .env.example file to create a new .env file
```shell
cp .env.example .env
```

#### 5. Generate the application key
```shell
php artisan key:generate
```

#### 6. Update the database connection settings in the .env file according to your database setup
```dotenv
DB_CONNECTION=****
DB_HOST=****
DB_PORT=****
DB_DATABASE=****
DB_USERNAME=****
DB_PASSWORD=****
```

#### 7. Run database migrations and seed the database
```shell
php artisan migrate --seed
```

#### 8. Clear the application cache
```shell
php artisan optimize:clear
```

## Starting the application
To start the application, you can use the built-in Laravel development server

```shell
php artisan serve
```
or you can directly use PHP built-in server
```shell
php -S localhost:8000 -t public/
```

or if you choose to use Laravel Valet, run the command below to have it available at [http://e-com-test.test](http://e-com-test.test)
```shell
valet link
```
or if you wish to test locally  with SSL, run the command below to have it available  at [https://e-com-test.test](https://e-com-test.test)
```shell
valet secure
```
## Running Tests
To run the tests for the application, you can use either of the following commands
```shell
php artisan test
```
or use 
```shell
vendon/bin/phpunit
```

Make sure to run these commands from the root folder of the application. The tests will help ensure that the application functions as expected and that any changes made in the future do not introduce regressions.

## API Documentation
You can find the published API documentation [here](https://documenter.getpostman.com/view/3172372/2sA35D5P1y)



# Part 2 

The part 2 of the assessment that contains a series of 4 questions can be found in the web route
