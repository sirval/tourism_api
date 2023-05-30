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

## Configuring Payment Gateway

The payment gateway used for this test is Paystack. 

To set up paystack all you need is to grab paystack credentials from you paystack dashboard <a href='https://dashboard.paystack.com/#/settings/developers' target="_blank">Here</a>. 

Reference `.env.example` for `.env` paystack naming convention for this project. However, some test paystack credentials sufficient to get this project running have been provided already.

- Note: This is a bad practice but was done for test purposes. All sensitive data should always be set in the `.env` file.

## Serving the project

- To start the server, run `php artisan serve`

- To maintain the project, run `php artisan down`

- To bring back project, run `php artisan up`

## Running Test

To run the PHPUnit test provided in this app, check `phpunit.xml` in the root directory of your project to confirm this code snippet is not commected
    <env name="DB_CONNECTION" value="mysql_test"/>
    <env name="DB_DATABASE" value=":tramango_test_db:"/>
and make sure all is configured in the `config/database.php` in the connections array,

1. If all are set, run `php artisan test` this makes sure we don't mess up with our main database.
2. if all are set, you will see about 15 passed test as seen in the screenshot below.

<a href="https://raw.githubusercontent.com/sirval/tramango_api/main/public/files/passed_test.png" target="_blank"><img src="https://raw.githubusercontent.com/sirval/tramango_api/main/public/files/passed_test.png" /></a>


- Other details as regards to the endpoints in Postman will be shared with you via email.

- For more Laravel commands and options, refer to the Laravel documentation.




