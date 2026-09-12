<?php

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] != "doctor") {
    header("Location: ../../view/auth/login.php");
    exit;
}

require "../../model/database/database.php";
require "../../model/doctor/appointment.php";
require "../../model/doctor/leave.php";

$doctor_id = $_SESSION["user_id"];

$_SESSION["doctor_appointments"] = get_doctor_appointments($conn, $doctor_id);
$_SESSION["doctor_leaves"] = get_doctor_leaves($conn, $doctor_id);

header("Location: ../../view/doctor/dashboard.php");
exit;
?>
