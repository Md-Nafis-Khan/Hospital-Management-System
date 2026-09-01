# CarePlus Hospital Management System

## Technology
- PHP
- MySQL
- MySQLi Procedural
- HTML and CSS
- MVC folder structure

## Important
This is an entry-level MVC project. It uses simple PHP functions instead of classes for controllers and models. Database operations use MySQLi procedural functions.

## Run the project
1. Put this folder inside `C:/xampp/htdocs/`.
2. Start Apache and MySQL in XAMPP.
3. Open phpMyAdmin.
4. Import `database/careplus_hospital.sql`.
5. Open `database/seed_demo.php` once to create demo accounts.
6. Delete `database/seed_demo.php` after setup.
7. Open `http://localhost/HMS-MVC-Ultimate/`.

## Demo accounts
- Admin: `admin` / `password`
- Doctor: `doctor` / `password`
- Patient: `patient` / `password`

## Registration
The login page provides separate links for creating a Patient account and a Doctor account.

## Architecture
- `app/models/` contains database functions.
- `app/controllers/` contains request and application logic.
- `app/views/` contains HTML/PHP pages.
- `config/` contains database configuration.
- `index.php` is the front controller and simple router.
- `public/assets/css/` contains the CSS files.
