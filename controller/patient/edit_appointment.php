<?php

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] != "patient") {
    header("Location: ../../view/auth/login.php");
    exit;
}

require "../../model/database/database.php";
require "../../model/patient/appointment.php";

$id = (int)($_GET["id"] ?? 0);
$appointment = get_patient_appointment($conn, $id, $_SESSION["user_id"]);

if ($appointment && $appointment["status"] == "pending") {
    $_SESSION["edit_appointment"] = $appointment;
} else {
    $_SESSION["message"] = "This appointment cannot be edited.";
    $_SESSION["message_type"] = "error";
}

header("Location: dashboard.php");
exit;
?>
