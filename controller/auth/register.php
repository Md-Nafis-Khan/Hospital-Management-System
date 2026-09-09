<?php

session_start();

require "../../model/database/database.php";
require "../../model/auth/user.php";

$username = trim($_POST["username"] ?? "");
$name = trim($_POST["name"] ?? "");
$email = trim($_POST["email"] ?? "");
$password = $_POST["password"] ?? "";
$confirm_password = $_POST["confirm_password"] ?? "";
$role = $_POST["role"] ?? "";

if ($username == "" || $name == "" || $email == "" || $password == "" || $confirm_password == "" || $role == "") {
    $_SESSION["message"] = "Please fill in all registration fields.";
    $_SESSION["message_type"] = "error";
    header("Location: ../../view/auth/login.php#register");
    exit;
}

if (strlen($username) < 3) {
    $_SESSION["message"] = "Username must contain at least 3 characters.";
    $_SESSION["message_type"] = "error";
    header("Location: ../../view/auth/login.php#register");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION["message"] = "Please enter a valid email.";
    $_SESSION["message_type"] = "error";
    header("Location: ../../view/auth/login.php#register");
    exit;
}

if (strlen($password) < 6) {
    $_SESSION["message"] = "Password must contain at least 6 characters.";
    $_SESSION["message_type"] = "error";
    header("Location: ../../view/auth/login.php#register");
    exit;
}

if ($password != $confirm_password) {
    $_SESSION["message"] = "Passwords do not match.";
    $_SESSION["message_type"] = "error";
    header("Location: ../../view/auth/login.php#register");
    exit;
}

if ($role != "patient" && $role != "doctor") {
    $_SESSION["message"] = "Please select patient or doctor.";
    $_SESSION["message_type"] = "error";
    header("Location: ../../view/auth/login.php#register");
    exit;
}

$existing_user = check_username_or_email($conn, $username, $email);
if ($existing_user) {
    if ($existing_user["username"] == $username) {
        $message = "Username already exists.";
    } else {
        $message = "Email already exists.";
    }

    $_SESSION["message"] = $message;
    $_SESSION["message_type"] = "error";
    header("Location: ../../view/auth/login.php#register");
    exit;
}

if (create_user($conn, $username, $name, $email, $password, $role)) {
    $_SESSION["message"] = "Registration successful. Please login.";
    $_SESSION["message_type"] = "success";
} else {
    $_SESSION["message"] = "Registration failed.";
    $_SESSION["message_type"] = "error";
}

header("Location: ../../view/auth/login.php");
exit;
?>
