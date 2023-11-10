1. go to project root directory in terminal and run folloing command.
composer install

2. create a database and update .env file for database connection.

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task
DB_USERNAME=root
DB_PASSWORD=

3. Run folloing command.
php artisan migrate
php artisan serve