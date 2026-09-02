<?php

session_start();

require_once __DIR__ . "/app/controllers/AuthController.php";
require_once __DIR__ . "/app/controllers/AdminController.php";
require_once __DIR__ . "/app/controllers/DoctorController.php";
require_once __DIR__ . "/app/controllers/PatientController.php";

$page = $_GET["page"] ?? "login";

if ($page == "admin") {
    $controller = new AdminController();
    $controller->showPage();
} elseif ($page == "doctor") {
    $controller = new DoctorController();
    $controller->showPage();
} elseif ($page == "patient") {
    $controller = new PatientController();
    $controller->showPage();
} elseif ($page == "logout") {
    $controller = new AuthController();
    $controller->logoutPage();
} else {
    $controller = new AuthController();
    $controller->showLoginPage();
}
