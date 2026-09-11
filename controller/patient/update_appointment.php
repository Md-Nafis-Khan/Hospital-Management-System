<?php

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] != "patient") {
    header("Location: ../../view/auth/login.php");
    exit;
}

require "../../model/database/database.php";
require "../../model/patient/appointment.php";

$patient_id = $_SESSION["user_id"];
$id = (int)($_POST["appointment_id"] ?? 0);
$doctor_id = (int)($_POST["doctor_id"] ?? 0);
$date = $_POST["date"] ?? "";
$time = $_POST["time"] ?? "";
$notes = trim($_POST["notes"] ?? "");

if ($id == 0 || $date == "" || $time == "") {
    $_SESSION["message"] = "Please choose date and time.";
    $_SESSION["message_type"] = "error";
} elseif ($date < date("Y-m-d")) {
    $_SESSION["message"] = "Appointment date cannot be in the past.";
    $_SESSION["message_type"] = "error";
} elseif (!appointment_slot_available($conn, $doctor_id, $date, $time, $id)) {
    $_SESSION["message"] = "This appointment time is already booked.";
    $_SESSION["message_type"] = "error";
} elseif (update_patient_appointment($conn, $id, $patient_id, $date, $time, $notes)) {
    $_SESSION["message"] = "Appointment updated.";
    $_SESSION["message_type"] = "success";
} else {
    $_SESSION["message"] = "Unable to update appointment.";
    $_SESSION["message_type"] = "error";
}

header("Location: dashboard.php");
exit;
?>
