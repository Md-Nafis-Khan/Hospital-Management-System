<?php

session_start();

require "../../model/database/database.php";
require "../../model/auth/user.php";

$email = trim($_POST["email"] ?? "");
$password = $_POST["password"] ?? "";

if ($email == "" || $password == "") {
    $_SESSION["message"] = "Please enter email and password.";
    $_SESSION["message_type"] = "error";
    header("Location: ../../view/auth/login.php");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION["message"] = "Please enter a valid email.";
    $_SESSION["message_type"] = "error";
    header("Location: ../../view/auth/login.php");
    exit;
}

$user = find_user_by_email($conn, $email);

if (!$user || !password_verify($password, $user["password"])) {
    $_SESSION["message"] = "Invalid email or password.";
    $_SESSION["message_type"] = "error";
    header("Location: ../../view/auth/login.php");
    exit;
}

$_SESSION["user_id"] = $user["id"];
$_SESSION["user_name"] = $user["name"];
$_SESSION["user_role"] = $user["role"];
$_SESSION["message"] = "Login successful.";
$_SESSION["message_type"] = "success";

header("Location: ../../controller/" . $user["role"] . "/dashboard.php");
exit;
?>
