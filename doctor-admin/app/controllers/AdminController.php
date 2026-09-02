<?php

require_once __DIR__ . "/../core/Controller.php";
require_once __DIR__ . "/../models/User.php";
require_once __DIR__ . "/../models/Appointment.php";
require_once __DIR__ . "/../models/Leave.php";

class AdminController extends Controller
{
    public function showPage()
    {
        if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] != "admin") { header("Location: index.php"); exit; }
        $this->view("admin");
    }

    public function getData()
    {
        $userModel = new User(); $appointmentModel = new Appointment(); $leaveModel = new Leave();
        echo json_encode(array("success" => true, "users" => $userModel->getAll(), "leaves" => $leaveModel->getAll(), "appointments" => $appointmentModel->countAll(), "pending_leaves" => $leaveModel->countPending()));
    }

    public function reviewLeave()
    {
        $status = $_POST["status"];
        if ($status != "approved" && $status != "rejected") { echo json_encode(array("success" => false, "message" => "Invalid decision.")); return; }
        $leaveModel = new Leave(); $leaveModel->review($_POST["leave_id"], $status);
        echo json_encode(array("success" => true, "message" => "Leave request updated."));
    }

    public function createUser()
    {
        $name = trim($_POST["name"]); $email = trim($_POST["email"]); $password = $_POST["password"]; $role = $_POST["role"];
        if ($name == "" || $email == "" || $password == "" || $role == "") { echo json_encode(array("success" => false, "message" => "Please fill in all fields.")); return; }
        if ($role != "patient" && $role != "doctor" && $role != "admin") { echo json_encode(array("success" => false, "message" => "Invalid role.")); return; }
        $userModel = new User();
        if ($userModel->findByEmail($email)) { echo json_encode(array("success" => false, "message" => "Email already exists.")); return; }
        $userModel->create($name, $email, $password, $role);
        echo json_encode(array("success" => true, "message" => "User created."));
    }

    public function updateUser()
    {
        $name = trim($_POST["name"]); $email = trim($_POST["email"]); $role = $_POST["role"];
        if ($name == "" || $email == "" || $role == "") { echo json_encode(array("success" => false, "message" => "Please fill in all fields.")); return; }
        if ($role != "patient" && $role != "doctor" && $role != "admin") { echo json_encode(array("success" => false, "message" => "Invalid role.")); return; }
        $userModel = new User(); $userModel->update($_POST["user_id"], $name, $email, $role);
        echo json_encode(array("success" => true, "message" => "User updated."));
    }

    public function deleteUser()
    {
        if ($_POST["user_id"] == $_SESSION["user_id"]) { echo json_encode(array("success" => false, "message" => "You cannot delete your own account.")); return; }
        $userModel = new User();
        try {
            $userModel->delete($_POST["user_id"]);
            echo json_encode(array("success" => true, "message" => "User deleted."));
        } catch (PDOException $error) {
            echo json_encode(array("success" => false, "message" => "Cannot delete this user because they have related appointments or leaves."));
        }
    }
}

if (isset($_GET["action"])) {
    if (session_status() == PHP_SESSION_NONE) { session_start(); }
    header("Content-Type: application/json");
    if (!isset($_SESSION["user_role"]) || $_SESSION["user_role"] != "admin") {
        echo json_encode(array("success" => false, "message" => "You are not allowed to do this."));
    } else {
        $action = $_GET["action"];
        $controller = new AdminController();
        if ($action == "admin_data") { $controller->getData(); }
        elseif ($action == "review_leave") { $controller->reviewLeave(); }
        elseif ($action == "create_user") { $controller->createUser(); }
        elseif ($action == "update_user") { $controller->updateUser(); }
        elseif ($action == "delete_user") { $controller->deleteUser(); }
        else { echo json_encode(array("success" => false, "message" => "Invalid action.")); }
    }
}