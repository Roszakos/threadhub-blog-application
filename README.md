### Blog web application where users can read and write articles, created with Laravel, Blade Templates, TailwindCSS and Alpine.js

## Getting started
**Requirements:**
- [Composer](https://getcomposer.org/download/)
- [PHP](https://www.php.net/downloads.php) (version 8.1 or newer)
- [Node.js](https://nodejs.org/en)

**Installation:**

1. Clone or download the repository

	`git clone https://github.com/Roszakos/threadhub-blog-application.git`

2. Rename .env.example file to .env
3. Fill .env file with your database information
4. Open console and cd to project root directory (threadhub-blog-application)
5. Run following commands:

 - Install required dependencies
   
	`composer install`

	`npm install`

 - Generate application key
   
    `php artisan key:generate`

 - Create storage symbolic link
   
    `php artisan storage:link`

 - Run database migrations
   
    `php artisan migrate`

- You can seed the database with dummy data if you'd like to

    `php artisan db:seed`
 
 - Run project
   
    `php artisan serve`

    `npm run dev`
6. View the project at localhost:8000
