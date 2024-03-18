# Thread hub
### Blog web application where users can read and write articles, created with Laravel, Blade Templates and Alpine.js

**Run project locally**

Requirements:
- [Composer](https://getcomposer.org/download/)
- PHP version 8.1 or newer

Installation:
1. Rename .env.example file to .env
1. Fill .env file with your database information
2. Open console and cd to project root directory (threadhub-blog-application-main)
3. Run following commands:

 - Install required dependencies
    > composer install

    > npm install

 - Generate application key
    > php artisan key:generate

 - Run database migrations
    > php artisan migrate

- You can seed the database with dummy data if you'd like to
    > php artisan db:seed
 
 - Run project
    > php artisan serve

    > npm run dev
5. View the project at localhost:8000
