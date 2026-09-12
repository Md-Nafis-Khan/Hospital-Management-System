<?php

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] != "doctor") {
    header("Location: ../../view/auth/login.php");
    exit;
}

require "../../model/database/database.php";
require "../../model/doctor/leave.php";

$doctor_id = $_SESSION["user_id"];
$start = $_POST["start_date"] ?? "";
$end = $_POST["end_date"] ?? "";
$reason = trim($_POST["reason"] ?? "");

if ($start == "" || $end == "" || $reason == "") {
    $_SESSION["message"] = "Please fill in all leave fields.";
    $_SESSION["message_type"] = "error";
} elseif ($end < $start) {
    $_SESSION["message"] = "End date cannot be before start date.";
    $_SESSION["message_type"] = "error";
} elseif (create_leave($conn, $doctor_id, $start, $end, $reason)) {
    $_SESSION["message"] = "Leave application submitted.";
    $_SESSION["message_type"] = "success";
} else {
    $_SESSION["message"] = "Unable to submit leave application.";
    $_SESSION["message_type"] = "error";
}

header("Location: dashboard.php");
exit;
?>
