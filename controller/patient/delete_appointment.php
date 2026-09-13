<?php

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] != "patient") {
    header("Location: ../../view/auth/login.php");
    exit;
}

require "../../model/database/database.php";
require "../../model/patient/appointment.php";

$id = (int)($_POST["appointment_id"] ?? 0);
$patient_id = $_SESSION["user_id"];

if ($id == 0) {
    $_SESSION["message"] = "Invalid appointment.";
    $_SESSION["message_type"] = "error";
} elseif (delete_patient_appointment($conn, $id, $patient_id)) {
    $_SESSION["message"] = "Appointment cancelled.";
    $_SESSION["message_type"] = "success";
} else {
    $_SESSION["message"] = "Unable to cancel appointment.";
    $_SESSION["message_type"] = "error";
}

header("Location: dashboard.php");
exit;
?>
