<?php

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] != "doctor") {
    header("Location: ../../view/auth/login.php");
    exit;
}

require "../../model/database/database.php";
require "../../model/doctor/leave.php";

$id = (int)($_POST["leave_id"] ?? 0);
$doctor_id = $_SESSION["user_id"];

if ($id == 0) {
    $_SESSION["message"] = "Invalid leave application.";
    $_SESSION["message_type"] = "error";
} elseif (delete_leave($conn, $id, $doctor_id)) {
    $_SESSION["message"] = "Leave application deleted.";
    $_SESSION["message_type"] = "success";
} else {
    $_SESSION["message"] = "Unable to delete leave application.";
    $_SESSION["message_type"] = "error";
}

header("Location: dashboard.php");
exit;
?>
