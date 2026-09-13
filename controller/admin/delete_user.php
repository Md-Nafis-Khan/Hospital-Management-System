<?php

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] != "admin") {
    header("Location: ../../view/auth/login.php");
    exit;
}

require "../../model/database/database.php";
require "../../model/admin/user.php";

$id = (int)($_POST["user_id"] ?? 0);

if ($id == 0) {
    $_SESSION["message"] = "Invalid user.";
    $_SESSION["message_type"] = "error";
} elseif ($id == $_SESSION["user_id"]) {
    $_SESSION["message"] = "You cannot delete your own account.";
    $_SESSION["message_type"] = "error";
} elseif (delete_user($conn, $id)) {
    $_SESSION["message"] = "User deleted successfully.";
    $_SESSION["message_type"] = "success";
} else {
    $_SESSION["message"] = "Cannot delete this user because related records exist.";
    $_SESSION["message_type"] = "error";
}

header("Location: dashboard.php");
exit;
?>
