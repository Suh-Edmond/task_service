## Task API

A Task Management API
This API provides the following Services

- Authenticate Users(Create user accounts and Login users)
- Create Tasks for authenticated users
- Retrieve all tasks by users
- Edit Task
- Delete Task
- Toggle Task State

### Framework used
- Laravel 10.0

### Package used
- Laravel Sanctum for authentication
- Scramble API documentation [https://scramble.dedoc.co] (Scramble API documentation).

### Server Requirements
- PHP ^8.1
- MySQL
 
### How to start locally
- Clone or download the project using the link, the latest code is on the *master* branch
- Open it in your favourite IDE and run `composer install`
- Run `cp .env.example .env`
- Generate the app key by running `php artisan key: generate`
- Setup your database connection in your *.env* file
- Run the migration using the command `php artisan migrate`
- Serve the application using `php artisan serve` and visit **http://13.60.224.90/docs/api#/** for the API documentation

### Run test
- `php artisan test`


