## Task API

A Task Management API
This API provides the following Services

- Authenticate Users(Create user accounts and Login users)
- Create Tasks for authenticated users
- Retrieve all tasks by users
- Edit Task
- Delete Task

### Framework used
- Laravel 10.0

### Package used
- Laravel Sanctum for authentication

### Server Requirements
- PHP 8.3
- MySQL
 
### How to start locally
- Clone the project using the link, the latest code is on the *master* branch
- Open it in your favourite IDE and run Composer install
- Generate the app key by running *php artisan key: generate*
- Setup your database connection in your *.env* file
- Run the migration using the command *php artisan migrate*
- You can view all the endpoints exposed from the /routes/api.php file.
- Serve the application using php artisan serve
- The app runs at  #### http://localhost:9000

###  
- Register: #### https://localhost:8000/api/public/auth/register ####
- Login: #### https://localhost:8000/api/public/auth/login ####
- Logout: #### https://localhost:8000/api/public/auth/logout ####
- Create Task: #### https://localhost:8000/api/protected/task/create ####
- Update task: #### https://localhost:8000/api/protected/tasks/task_id/update ####
- Delete Task: #### https://localhost:8000/api/protected/tasks/task_id/users/user_id ####
- Fetch Tasks: #### https://localhost:8000/api/protected/tasks/users/user_id ####


