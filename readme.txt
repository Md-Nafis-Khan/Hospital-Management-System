CARE PLUS HOSPITAL MANAGEMENT SYSTEM
====================================

This version is intentionally rewritten for beginner-friendly MVC.

IMPORTANT:
There is NO index.php and NO front controller.

Only three MVC folders are used:
    model/
    view/
    controller/

Inside those folders, project-specific folders are separated by role/occasion.

PROJECT STRUCTURE
-----------------
model/
    database/
        database.php
        schema.sql
    auth/
        user.php
    admin/
        user.php
        dashboard.php
    patient/
        doctor.php
        appointment.php
    doctor/
        appointment.php
        leave.php

view/
    auth/
        login.php
    admin/
        dashboard.php
    patient/
        dashboard.php
    doctor/
        dashboard.php
    assets/
        css/
        js/
        images/

controller/
    auth/
        login.php
        register.php
        logout.php
    admin/
        dashboard.php
        create_user.php
        update_user.php
        delete_user.php
        review_leave.php
    patient/
        dashboard.php
        book_appointment.php
        edit_appointment.php
        update_appointment.php
        delete_appointment.php
    doctor/
        dashboard.php
        update_appointment.php
        apply_leave.php
        edit_leave.php
        update_leave.php
        delete_leave.php

FLOW
----
1. The user opens view/auth/login.php.
2. A view form sends the request to a controller.
3. The controller validates the data.
4. The controller calls the appropriate procedural model function.
5. The model performs database work.
6. The controller stores messages/data in $_SESSION.
7. The controller redirects to the required view.
8. The view reads the session data and displays it.

The views NEVER require or call model files.

TECHNOLOGY
----------
- PHP
- MySQL/MariaDB
- MySQLi procedural style
- HTML
- CSS
- JavaScript
- Sessions
- Prepared statements
- No PHP classes
- No front controller
- No require_once
- No __DIR__
- No unnecessary AJAX

AJAX
----
No AJAX is used in this version because the current operations do not need
asynchronous updates. Normal form submission keeps the MVC flow simple:
View -> Controller -> Model -> Session -> View.

If a future feature genuinely needs live/asynchronous data, AJAX can be added
only to that specific feature.

VALIDATION
----------
JavaScript performs client-side validation and uses alert().
PHP repeats validation on the server side.

DATABASE SETUP
--------------
1. Start Apache and MySQL in XAMPP.
2. Open phpMyAdmin.
3. Import:
       model/database/schema.sql
4. The database name is:
       hs
5. Default database settings:
       Host: localhost
       User: root
       Password: empty

DEFAULT ACCOUNTS
----------------
Admin:
    admin@admin.com
    admin123

Doctor:
    doctor@doctor.com
    doctor123

Patient:
    patient@patient.com
    patient123

START PAGE
----------
Because there is intentionally no index.php, open:

    http://localhost/HMS/view/auth/login.php

Replace HMS with the actual project folder name.

FUNCTIONALITIES
---------------
Patient:
- Login/register
- View doctors
- Book appointment
- Edit pending appointment
- Cancel pending appointment
- View appointment status

Doctor:
- View appointments
- Approve appointments
- Complete appointments
- Cancel appointments
- Apply for leave
- Edit pending leave
- Delete pending leave
- View leave status

Admin:
- View statistics
- Add users
- Update users
- Delete users
- Review doctor leave requests

AJAX features
-------------
1. Registration checks username and email availability using AJAX.
2. Patient appointment date/time availability is checked using AJAX.
3. Doctor appointment status is updated using AJAX without refreshing the dashboard.

The AJAX requests go to controller files. The controllers call the model and return a simple JSON response. Normal form submissions still use the View -> Controller -> Model -> Session -> View flow.
