<?php

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] != "doctor") {
    header("Location: ../../view/auth/login.php");
    exit;
}

require "../../model/database/database.php";
require "../../model/doctor/leave.php";

$doctor_id = $_SESSION["user_id"];
$id = (int)($_POST["leave_id"] ?? 0);
$start = $_POST["start_date"] ?? "";
$end = $_POST["end_date"] ?? "";
$reason = trim($_POST["reason"] ?? "");

if ($id == 0 || $start == "" || $end == "" || $reason == "") {
    $_SESSION["message"] = "Please fill in all leave fields.";
    $_SESSION["message_type"] = "error";
} elseif ($end < $start) {
    $_SESSION["message"] = "End date cannot be before start date.";
    $_SESSION["message_type"] = "error";
} elseif (update_leave($conn, $id, $doctor_id, $start, $end, $reason)) {
    $_SESSION["message"] = "Leave application updated.";
    $_SESSION["message_type"] = "success";
} else {
    $_SESSION["message"] = "Unable to update leave application.";
    $_SESSION["message_type"] = "error";
}

header("Location: dashboard.php");
exit;
?>
