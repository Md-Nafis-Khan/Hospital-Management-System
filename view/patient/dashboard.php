<?php

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] != "patient") {
    header("Location: ../../view/auth/login.php");
    exit;
}

require "../../model/database/database.php";
require "../../model/patient/doctor.php";
require "../../model/patient/appointment.php";

$patient_id = $_SESSION["user_id"];

$_SESSION["patient_doctors"] = get_doctors($conn);
$_SESSION["patient_appointments"] = get_patient_appointments($conn, $patient_id);

header("Location: ../../view/patient/dashboard.php");
exit;
?>
