.
Mini School Management System (WAMP Setup)

A Mini School Management System using Laravel (backend API) and Vue 3 (frontend SPA) with role-based access for Admin, Teacher, and Student.

Requirements
WAMP (Apache + MySQL + PHP)
PHP >= 8.1
Node.js & NPM
PHP >= 8.1
Node.js & NPM/Yarn
MySQL or MariaDB
Laravel 10
Vue 3 + Vite


********Steps *******
1.Open WAMP (www folder), usually: C:\wamp64\www

2.Create a folder for the project, e.g., mini-school.

3.Open Command Prompt and navigate to the project folder:
   cd C:\wamp64\www\mini-school

4.Clone the repository:
cmd
git clone https://github.com/Razanhroub/yasmina.git
code .
cd mini-school-management
cd \yasmina\Yasmina_Backend

5.Install backend dependencies:
    on terminal run: 
   composer install

6.Copy .env.example to .env:
    cp .env.example .env

7.Generate application key:
    php artisan key:generate

8.Configure database in .env file:

DB_DATABASE=your_database_name
DB_USERNAME=your_db_username
DB_PASSWORD=your_db_password

9-Create a database in MySQL with the name you set in the .env file.

10.migrate and seed the database:
    php artisan migrate --seed
    or can 
    php artisan migrate
    php artisan db:seed --class=RolesSeeder
    php artisan db:seed

    you will have the deafult user with admin role :
    ***********************
    Email: razan.b.alhroub@gmail.com
    Password: Raz_2001
    ***********************
11.Sanctum & Spatie Roles & Permissions Setup

composer require laravel/sanctum
    php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
    php artisan migrate
    composer require spatie/laravel-permission
    php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
    php artisan migrate
    php artisan db:seed --class=RolesSeeder

12.Start the Laravel development server:
    php artisan serve   

13.Open a new terminal for the frontend:
    cd \yasmina\Yasmina_Frontend

14.Install frontend dependencies:

    npm install
    npm install sweetalert2

15.Start the frontend development server:
    npm run dev






