Lawrence Peñanos Exam for Eastadvantage

Requirements
Laravel 8 - Php 7.4

Please make sure that php 7.4 is installed on your computer.


Steps
1. Download the repository.
2. Setup your .env you can copy the env.example for your reference.
3. Setup the database you will be using apply it correctly in the env if you use different database name. (in my case I name it eastadvantage)
4. Run composer install on your terminal (App route).
5. Run php artisan migrate:fresh.
6. Run php artisan db:seed.
7. Run php artisan key:generate.
8. Run php artisan serve

after this you are now online. Please make sure that the
Laravel app is running under localhost:8000 to avoid cors error.

