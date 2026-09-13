<?php
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] != "patient") {
    http_response_code(403);
    header("Content-Type: application/json");
    echo json_encode(array("available" => false, "message" => "Please login first."));
    exit;
}

require "../../model/database/database.php";
require "../../model/patient/appointment.php";

$doctor_id = (int)($_POST["doctor_id"] ?? 0);
$date = $_POST["date"] ?? "";
$time = $_POST["time"] ?? "";
$appointment_id = (int)($_POST["appointment_id"] ?? 0);

if ($doctor_id == 0 || $date == "" || $time == "") {
    echo json_encode(array("available" => false, "message" => "Please choose doctor, date and time."));
    exit;
}

$available = appointment_slot_available($conn, $doctor_id, $date, $time, $appointment_id);

header("Content-Type: application/json");
echo json_encode(array(
    "available" => $available,
    "message" => $available ? "Appointment time is available." : "This appointment time is already booked."
));
exit;
?>
