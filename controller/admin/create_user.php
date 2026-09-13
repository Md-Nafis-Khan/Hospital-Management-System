<?php

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] != "admin") {
    header("Location: ../../view/auth/login.php");
    exit;
}

require "../../model/database/database.php";
require "../../model/auth/user.php";

$name = trim($_POST["name"] ?? "");
$email = trim($_POST["email"] ?? "");
$password = $_POST["password"] ?? "";
$role = $_POST["role"] ?? "";

if ($name == "" || $email == "" || $password == "" || $role == "") {
    $_SESSION["message"] = "Please fill in all fields.";
    $_SESSION["message_type"] = "error";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION["message"] = "Please enter a valid email.";
    $_SESSION["message_type"] = "error";
} elseif (strlen($password) < 6) {
    $_SESSION["message"] = "Password must contain at least 6 characters.";
    $_SESSION["message_type"] = "error";
} elseif ($role != "patient" && $role != "doctor" && $role != "admin") {
    $_SESSION["message"] = "Invalid role.";
    $_SESSION["message_type"] = "error";
} elseif (find_user_by_email($conn, $email)) {
    $_SESSION["message"] = "Email already exists.";
    $_SESSION["message_type"] = "error";
} elseif (create_user($conn, strtolower(str_replace(" ", "", $name)), $name, $email, $password, $role)) {
    $_SESSION["message"] = "User created successfully.";
    $_SESSION["message_type"] = "success";
} else {
    $_SESSION["message"] = "Unable to create user.";
    $_SESSION["message_type"] = "error";
}

header("Location: dashboard.php");
exit;
?>
