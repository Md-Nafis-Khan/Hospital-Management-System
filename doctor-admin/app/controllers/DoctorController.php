<?php

require_once __DIR__ . "/../core/Controller.php";
require_once __DIR__ . "/../models/Appointment.php";
require_once __DIR__ . "/../models/Leave.php";

class DoctorController extends Controller
{
    public function showPage()
    {
        if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] != "doctor") { header("Location: index.php"); exit; }
        $this->view("doctor");
    }

    public function getData()
    {
        $appointmentModel = new Appointment(); $leaveModel = new Leave();
        echo json_encode(array("success" => true, "appointments" => $appointmentModel->getForDoctor($_SESSION["user_id"]), "leaves" => $leaveModel->getForDoctor($_SESSION["user_id"])));
    }

    public function updateAppointment()
    {
        $status = $_POST["status"];
        if ($status != "approved" && $status != "completed" && $status != "cancelled") { echo json_encode(array("success" => false, "message" => "Invalid status.")); return; }
        $appointmentModel = new Appointment(); $appointmentModel->updateStatus($_POST["appointment_id"], $_SESSION["user_id"], $status);
        echo json_encode(array("success" => true, "message" => "Appointment updated."));
    }

    public function applyLeave()
    {
        if ($_POST["start_date"] == "" || $_POST["end_date"] == "" || trim($_POST["reason"]) == "") { echo json_encode(array("success" => false, "message" => "Please fill in all leave fields.")); return; }
        $leaveModel = new Leave(); $leaveModel->create($_SESSION["user_id"], $_POST["start_date"], $_POST["end_date"], trim($_POST["reason"]));
        echo json_encode(array("success" => true, "message" => "Leave application submitted."));
    }

    public function updateLeave()
    {
        if ($_POST["start_date"] == "" || $_POST["end_date"] == "" || trim($_POST["reason"]) == "") { echo json_encode(array("success" => false, "message" => "Please fill in all leave fields.")); return; }
        $leaveModel = new Leave(); $leaveModel->update($_POST["leave_id"], $_SESSION["user_id"], $_POST["start_date"], $_POST["end_date"], trim($_POST["reason"]));
        echo json_encode(array("success" => true, "message" => "Leave application updated."));
    }

    public function deleteLeave()
    {
        $leaveModel = new Leave(); $leaveModel->delete($_POST["leave_id"], $_SESSION["user_id"]);
        echo json_encode(array("success" => true, "message" => "Leave application deleted."));
    }
}

if (isset($_GET["action"])) {
    if (session_status() == PHP_SESSION_NONE) { session_start(); }
    header("Content-Type: application/json");
    if (!isset($_SESSION["user_role"]) || $_SESSION["user_role"] != "doctor") {
        echo json_encode(array("success" => false, "message" => "You are not allowed to do this."));
    } else {
        $action = $_GET["action"];
        $controller = new DoctorController();
        if ($action == "doctor_data") { $controller->getData(); }
        elseif ($action == "update_appointment") { $controller->updateAppointment(); }
        elseif ($action == "apply_leave") { $controller->applyLeave(); }
        elseif ($action == "update_leave") { $controller->updateLeave(); }
        elseif ($action == "delete_leave") { $controller->deleteLeave(); }
        else { echo json_encode(array("success" => false, "message" => "Invalid action.")); }
    }
}