<?php

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] != "admin") {
    header("Location: ../../view/auth/login.php");
    exit;
}

require "../../model/database/database.php";
require "../../model/admin/dashboard.php";

$id = (int)($_POST["leave_id"] ?? 0);
$status = $_POST["status"] ?? "";

if ($id == 0 || ($status != "approved" && $status != "rejected")) {
    $_SESSION["message"] = "Invalid leave request.";
    $_SESSION["message_type"] = "error";
} elseif (review_leave($conn, $id, $status)) {
    $_SESSION["message"] = "Leave request updated.";
    $_SESSION["message_type"] = "success";
} else {
    $_SESSION["message"] = "Unable to update leave request.";
    $_SESSION["message_type"] = "error";
}

header("Location: dashboard.php");
exit;
?>
