<?php

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] != "doctor") {
    header("Location: ../../view/auth/login.php");
    exit;
}

require "../../model/database/database.php";
require "../../model/doctor/appointment.php";

$id = (int)($_POST["appointment_id"] ?? 0);
$status = $_POST["status"] ?? "";
$doctor_id = $_SESSION["user_id"];

if ($id == 0 || ($status != "approved" && $status != "completed" && $status != "cancelled")) {
    $_SESSION["message"] = "Invalid appointment update.";
    $_SESSION["message_type"] = "error";
} elseif (update_appointment_status($conn, $id, $doctor_id, $status)) {
    $_SESSION["message"] = "Appointment updated.";
    $_SESSION["message_type"] = "success";
} else {
    $_SESSION["message"] = "Unable to update appointment.";
    $_SESSION["message_type"] = "error";
}

header("Location: dashboard.php");
exit;
?>
