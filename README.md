# Tramango API Project

This repository contains a Laravel project that you can clone and install on your local machine.

## Prerequisites

Before getting started, ensure that you have the following softwares installed on your system:

- PHP (>= 8.0)
- Composer
- MySQL
- Git

## Clone the Repository

1. Open your terminal or command prompt.

2. Change to the directory where you want to clone the project.

3. Run the following command to clone the repository: `git clone https://github.com/sirval/tramango_api.git`

## Installation

Follow these steps to set up and install the Laravel project:

1. Change into the project directory: `cd project_directory`

2. Install the project dependencies using Composer: `composer install`

3. Create a copy of the `.env.example` file and rename it to `.env`:

4. Generate an application key: `php artisan key:generate`

5. Generate an JWT Secret key: `php artisan jwt:secret`

6. Configure the app main database connection and test database connection by updating the `.env` file with your database credentials.

7. Run database migrations to create the required tables: `php artisan migrate`

8. Seed the database with initial sample data: `php artisan db:seed` This will generate roles, TravelOption.

9. Start the development server: `php artisan serve`

By default, the application will be accessible at `http://localhost:8000`.

## Running Test

To run the PHPUnit test provided in this app, check `phpunit.xml` in the root directory of your project to confirm this code snippet is not commected
    <env name="DB_CONNECTION" value="mysql_test"/>
    <env name="DB_DATABASE" value=":tramango_test_db:"/>
and make sure all is configured in the `config/database.php` in the connections array,
1. If all are set, run `php artisan test` this makes sure we don't mess up with our main database.
2. if all is set, you will see about 15 passed test as seen in the screenshot below.


- Other details as regards to the endpoints will be shared with you via email.

- For more Laravel commands and options, refer to the Laravel documentation.




