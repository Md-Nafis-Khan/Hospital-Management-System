<?php
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] != "doctor") {
    http_response_code(403);
    header("Content-Type: application/json");
    echo json_encode(array("success" => false, "message" => "Please login first."));
    exit;
}

require "../../model/database/database.php";
require "../../model/doctor/appointment.php";

$id = (int)($_POST["appointment_id"] ?? 0);
$status = $_POST["status"] ?? "";
$doctor_id = $_SESSION["user_id"];

if ($id == 0 || ($status != "approved" && $status != "completed" && $status != "cancelled")) {
    $message = "Invalid appointment update.";
    $success = false;
} elseif (update_appointment_status($conn, $id, $doctor_id, $status)) {
    $message = "Appointment updated successfully.";
    $success = true;
    $_SESSION["message"] = $message;
    $_SESSION["message_type"] = "success";
} else {
    $message = "Unable to update appointment.";
    $success = false;
}

header("Content-Type: application/json");
echo json_encode(array("success" => $success, "message" => $message, "status" => $status));
exit;
?>
