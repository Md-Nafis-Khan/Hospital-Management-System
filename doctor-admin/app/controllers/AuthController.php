<?php

require_once __DIR__ . "/../core/Controller.php";
require_once __DIR__ . "/../models/User.php";

class AuthController extends Controller
{
    public function showLoginPage()
    {
        if (isset($_SESSION["user_role"])) {
            header("Location: index.php?page=" . $_SESSION["user_role"]);
            exit;
        }
        $this->view("login");
    }

    public function logoutPage()
    {
        session_destroy();
        header("Location: index.php");
        exit;
    }

    public function login()
    {
        $email = trim($_POST["email"]);
        $password = $_POST["password"];
        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user["password"])) {
            echo json_encode(array("success" => false, "message" => "Invalid email or password."));
            return;
        }

        $_SESSION["user_id"] = $user["id"];
        $_SESSION["user_name"] = $user["name"];
        $_SESSION["user_role"] = $user["role"];
        echo json_encode(array("success" => true, "role" => $user["role"], "message" => "Login successful."));
    }

    public function register()
    {
        $name = trim($_POST["name"]);
        $email = trim($_POST["email"]);
        $password = $_POST["password"];
        $role = $_POST["role"];
        $userModel = new User();

        if ($name == "" || $email == "" || $password == "" || $role == "") {
            echo json_encode(array("success" => false, "message" => "Please fill in all fields."));
            return;
        }

        if ($password != $_POST["confirm_password"]) {
            echo json_encode(array("success" => false, "message" => "Passwords do not match."));
            return;
        }

        if ($role != "patient" && $role != "doctor") {
            echo json_encode(array("success" => false, "message" => "Please select patient or doctor."));
            return;
        }

        if ($userModel->findByEmail($email)) {
            echo json_encode(array("success" => false, "message" => "Email already exists."));
            return;
        }

        $userModel->create($name, $email, $password, $role);
        echo json_encode(array("success" => true, "message" => "Registration successful. Please login."));
    }

    public function logout()
    {
        session_destroy();
        echo json_encode(array("success" => true));
    }
}

if (isset($_GET["action"])) {
    if (session_status() == PHP_SESSION_NONE) { session_start(); }
    header("Content-Type: application/json");
    $action = $_GET["action"];
    $controller = new AuthController();
    if ($action == "login") { $controller->login(); }
    elseif ($action == "register") { $controller->register(); }
    elseif ($action == "logout") { $controller->logout(); }
    else { echo json_encode(array("success" => false, "message" => "Invalid action.")); }
}