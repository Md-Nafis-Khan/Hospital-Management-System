<?php

require_once __DIR__ . "/../core/Controller.php";
require_once __DIR__ . "/../models/User.php";
require_once __DIR__ . "/../models/Appointment.php";

class PatientController extends Controller
{
    public function showPage()
    {
        if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] != "patient") { header("Location: index.php"); exit; }
        $this->view("patient");
    }

    public function getData()
    {
        $userModel = new User(); $appointmentModel = new Appointment();
        echo json_encode(array("success" => true, "doctors" => $userModel->getDoctors(), "appointments" => $appointmentModel->getForPatient($_SESSION["user_id"])));
    }

    public function book()
    {
        $doctorId = $_POST["doctor_id"]; $date = $_POST["date"]; $time = $_POST["time"]; $notes = trim($_POST["notes"]);
        if ($doctorId == "" || $date == "" || $time == "") { echo json_encode(array("success" => false, "message" => "Please choose doctor, date and time.")); return; }
        $appointmentModel = new Appointment(); $appointmentModel->create($_SESSION["user_id"], $doctorId, $date, $time, $notes);
        echo json_encode(array("success" => true, "message" => "Appointment request sent."));
    }

    public function update()
    {
        $date = $_POST["date"]; $time = $_POST["time"]; $notes = trim($_POST["notes"]);
        if ($date == "" || $time == "") { echo json_encode(array("success" => false, "message" => "Please choose date and time.")); return; }
        $appointmentModel = new Appointment(); $appointmentModel->updateByPatient($_POST["appointment_id"], $_SESSION["user_id"], $date, $time, $notes);
        echo json_encode(array("success" => true, "message" => "Appointment updated."));
    }

    public function delete()
    {
        $appointmentModel = new Appointment(); $appointmentModel->deleteByPatient($_POST["appointment_id"], $_SESSION["user_id"]);
        echo json_encode(array("success" => true, "message" => "Appointment cancelled."));
    }
}

if (isset($_GET["action"])) {
    if (session_status() == PHP_SESSION_NONE) { session_start(); }
    header("Content-Type: application/json");
    if (!isset($_SESSION["user_role"]) || $_SESSION["user_role"] != "patient") {
        echo json_encode(array("success" => false, "message" => "You are not allowed to do this."));
    } else {
        $action = $_GET["action"];
        $controller = new PatientController();
        if ($action == "patient_data") { $controller->getData(); }
        elseif ($action == "book_appointment") { $controller->book(); }
        elseif ($action == "update_appointment_patient") { $controller->update(); }
        elseif ($action == "delete_appointment") { $controller->delete(); }
        else { echo json_encode(array("success" => false, "message" => "Invalid action.")); }
    }
}