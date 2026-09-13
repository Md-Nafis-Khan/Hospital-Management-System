<?php

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] != "admin") {
    header("Location: ../../view/auth/login.php");
    exit;
}

require "../../model/database/database.php";
require "../../model/admin/user.php";

$id = (int)($_POST["user_id"] ?? 0);
$name = trim($_POST["name"] ?? "");
$email = trim($_POST["email"] ?? "");
$role = $_POST["role"] ?? "";

if ($id == 0 || $name == "" || $email == "" || $role == "") {
    $_SESSION["message"] = "Please fill in all fields.";
    $_SESSION["message_type"] = "error";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION["message"] = "Please enter a valid email.";
    $_SESSION["message_type"] = "error";
} elseif ($role != "patient" && $role != "doctor" && $role != "admin") {
    $_SESSION["message"] = "Invalid role.";
    $_SESSION["message_type"] = "error";
} elseif (email_exists_for_other_user($conn, $email, $id)) {
    $_SESSION["message"] = "That email is already used by another user.";
    $_SESSION["message_type"] = "error";
} elseif (update_user($conn, $id, $name, $email, $role)) {
    $_SESSION["message"] = "User updated successfully.";
    $_SESSION["message_type"] = "success";
} else {
    $_SESSION["message"] = "Unable to update user.";
    $_SESSION["message_type"] = "error";
}

header("Location: dashboard.php");
exit;
?>
