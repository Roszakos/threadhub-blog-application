# Thread hub
### Blog web application

**Run project locally**

Requirements:
- [Composer](https://getcomposer.org/download/)
- PHP version 8.1 and newer

Installation:
1. Rename .env.example file to .env
1. Fill .env file with your database information
2. Open console and cd to project root directory (threadhub-blog-application-main)
3. Run following commands
```
> composer install

> npm install

> php artisan key:generate

> php artisan migrate

> php artisan serve

> npm run dev
```
4. View the project at localhost:8000
