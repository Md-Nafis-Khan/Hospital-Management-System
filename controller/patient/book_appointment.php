<?php

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] != "patient") {
    header("Location: ../../view/auth/login.php");
    exit;
}

require "../../model/database/database.php";
require "../../model/patient/appointment.php";

$patient_id = $_SESSION["user_id"];
$doctor_id = (int)($_POST["doctor_id"] ?? 0);
$date = $_POST["date"] ?? "";
$time = $_POST["time"] ?? "";
$notes = trim($_POST["notes"] ?? "");

if ($doctor_id == 0 || $date == "" || $time == "") {
    $_SESSION["message"] = "Please choose doctor, date and time.";
    $_SESSION["message_type"] = "error";
} elseif ($date < date("Y-m-d")) {
    $_SESSION["message"] = "Appointment date cannot be in the past.";
    $_SESSION["message_type"] = "error";
} elseif (!appointment_slot_available($conn, $doctor_id, $date, $time)) {
    $_SESSION["message"] = "This appointment time is already booked.";
    $_SESSION["message_type"] = "error";
} elseif (create_appointment($conn, $patient_id, $doctor_id, $date, $time, $notes)) {
    $_SESSION["message"] = "Appointment request sent.";
    $_SESSION["message_type"] = "success";
} else {
    $_SESSION["message"] = "Unable to book appointment.";
    $_SESSION["message_type"] = "error";
}

header("Location: dashboard.php");
exit;
?>
